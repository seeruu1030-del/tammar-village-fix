<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\Invoice;
use App\Models\SavingsTransaction;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class DashboardController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $user = Auth::user();
        $resident = $user->resident;

        if (!$resident) {
            // Handle case where user is not linked to a resident record
            return view('warga.dashboard', [
                'resident' => null,
                'announcements' => Announcement::where('is_active', true)->latest()->take(5)->get(),
                'invoices' => collect(),
                'savings_balance' => 0,
                'savings_transactions' => collect(),
                'active_programs' => collect(),
            ]);
        }

        $announcements = Announcement::where('is_active', true)->latest()->take(5)->get();
        
        $invoices = Invoice::where('resident_id', $resident->id)
            ->orderBy('id', 'desc')
            ->get();

        $savings_transactions = SavingsTransaction::where('resident_id', $resident->id)
            ->with('program')
            ->latest()
            ->get();

        $savings_balance = SavingsTransaction::where('resident_id', $resident->id)
            ->where('status', 'success')
            ->sum('amount');

        $active_programs = \App\Models\SavingsProgram::where('status', 'active')->get();

        return view('warga.dashboard', compact(
            'resident', 
            'announcements', 
            'invoices', 
            'savings_transactions', 
            'savings_balance',
            'active_programs'
        ));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        $user->save();

        // Sync with Resident email if exists
        if ($user->resident) {
            $user->resident->update(['email' => $request->email]);
        }

        return back()->with('success', 'Akun berhasil diperbarui. Sekarang Anda bisa login menggunakan email ini.');
    }

    public function updateProfile(Request $request)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:50',
            'telegram_id' => 'nullable|string|max:100',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|max:255',
        ]);

        $resident->update($validated);
        
        $userData = ['name' => $validated['name']];
        if (isset($validated['email'])) {
            $userData['email'] = $validated['email'];
        }
        
        Auth::user()->update($userData);

        return redirect(route('warga.dashboard') . '#view-profil?tab=personal')->with('success', 'Informasi profil data diri berhasil diperbarui.');
    }

    public function storeFamily(Request $request)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'relationship' => 'required|string|max:50',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
        ]);
        
        $validated['status'] = 'Belum Verifikasi';
        
        $resident->familyMembers()->create($validated);
        return redirect(route('warga.dashboard') . '#view-profil?tab=family')->with('success', 'Anggota keluarga berhasil ditambahkan dan menunggu verifikasi.');
    }

    public function destroyFamily($id)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $member = \App\Models\FamilyMember::where('resident_id', $resident->id)->findOrFail($id);
        $member->delete();
        
        return redirect(route('warga.dashboard') . '#view-profil?tab=family')->with('success', 'Anggota keluarga berhasil dihapus.');
    }

    public function storeDocument(Request $request)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $request->validate([
            'name' => 'required|string|max:100',
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $file = $request->file('document');
        $path = $file->store('documents', 'public');

        $resident->documents()->create([
            'name' => $request->name,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
        ]);

        return redirect(route('warga.dashboard') . '#view-profil?tab=document')->with('success', 'Dokumen (' . $request->name . ') berhasil diunggah.');
    }

    public function destroyDocument($id)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $document = \App\Models\ResidentDocument::where('resident_id', $resident->id)->findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
        $document->delete();
        
        return redirect(route('warga.dashboard') . '#view-profil?tab=document')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function storeVehicle(Request $request)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'type' => 'required|in:car,motor',
            'brand_model_color' => 'nullable|string|max:255',
        ]);
        
        $validated['status'] = 'PENDING';
        
        $resident->vehicles()->create($validated);
        return redirect(route('warga.dashboard') . '#view-profil?tab=vehicle')->with('success', 'Kendaraan berhasil didaftarkan dan menunggu persetujuan Admin.');
    }

    public function destroyVehicle($id)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $vehicle = \App\Models\Vehicle::where('resident_id', $resident->id)->findOrFail($id);
        $vehicle->delete();
        
        return redirect(route('warga.dashboard') . '#view-profil?tab=vehicle')->with('success', 'Kendaraan berhasil dihapus dari profil Anda.');
    }

    public function submitPayment(Request $request)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $request->validate([
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
            'payment_method' => 'required|in:manual,midtrans',
            'payment_proof' => 'required_if:payment_method,manual|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->payment_method == 'manual') {
            $file = $request->file('payment_proof');
            $path = $file->store('payment_proofs', 'public');

            Invoice::whereIn('id', $request->invoice_ids)
                ->where('resident_id', $resident->id)
                ->where('status', 'unpaid')
                ->update([
                    'status' => 'pending_verification',
                    'payment_method' => 'manual',
                    'proof_path' => $path,
                    'payment_date' => now(),
                ]);

            return redirect(route('warga.dashboard') . '#view-iuran')->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi Admin.');
        } else {
            // Midtrans Logic
            $invoices = Invoice::whereIn('id', $request->invoice_ids)
                ->where('resident_id', $resident->id)
                ->where('status', 'unpaid')
                ->get();

            if ($invoices->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada tagihan yang bisa dibayar.'
                ], 400);
            }

            $totalAmount = 0;
            $itemDetails = [];
            
            foreach ($invoices as $inv) {
                $price = (int) $inv->amount;
                $totalAmount += $price;
                $itemDetails[] = [
                    'id' => (string) $inv->id,
                    'price' => $price,
                    'quantity' => 1,
                    'name' => substr('Iuran ' . \Carbon\Carbon::parse($inv->period . '-01')->translatedFormat('F Y'), 0, 50),
                ];
            }

            $orderId = 'INV-' . time() . '-' . $resident->id;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $resident->name,
                    'email' => Auth::user()->email ?? $resident->email,
                    'phone' => $resident->contact ?? '',
                ],
                'item_details' => $itemDetails,
            ];

            try {
                $snapToken = Snap::getSnapToken($params);

                Invoice::whereIn('id', $invoices->pluck('id'))
                    ->update([
                        'status' => 'pending_verification', // Ubah status agar tombol Cek Status muncul
                        'payment_method' => 'midtrans',
                        'midtrans_order_id' => $orderId,
                        'midtrans_snap_token' => $snapToken,
                    ]);

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_id' => $orderId
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Midtrans Snap Error: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mendapatkan token: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function submitSavingsPayment(Request $request)
    {
        $resident = Auth::user()->resident;
        if (!$resident) return back();

        $request->validate([
            'savings_program_id' => 'required|exists:savings_programs,id',
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|in:manual,midtrans',
            'payment_proof' => 'required_if:payment_method,manual|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $program = \App\Models\SavingsProgram::findOrFail($request->savings_program_id);

        if ($request->payment_method == 'manual') {
            $file = $request->file('payment_proof');
            $path = $file->store('payment_proofs', 'public');

            SavingsTransaction::create([
                'resident_id' => $resident->id,
                'savings_program_id' => $program->id,
                'amount' => $request->amount,
                'transaction_date' => now(),
                'type' => 'deposit',
                'payment_method' => 'manual',
                'proof_path' => $path,
                'status' => 'pending_verification',
            ]);

            return redirect(route('warga.dashboard') . '#view-tabungan')->with('success', 'Bukti transfer tabungan berhasil diunggah. Menunggu verifikasi Admin.');
        } else {
            // Midtrans Logic
            $orderId = 'SAV-' . time() . '-' . $resident->id;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $request->amount,
                ],
                'customer_details' => [
                    'first_name' => $resident->name,
                    'email' => Auth::user()->email ?? $resident->email,
                    'phone' => $resident->contact ?? '',
                ],
                'item_details' => [[
                    'id' => 'PROG-' . $program->id,
                    'price' => (int) $request->amount,
                    'quantity' => 1,
                    'name' => 'Setoran ' . $program->name,
                ]],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);

                SavingsTransaction::create([
                    'resident_id' => $resident->id,
                    'savings_program_id' => $program->id,
                    'amount' => $request->amount,
                    'transaction_date' => now(),
                    'type' => 'deposit',
                    'payment_method' => 'midtrans',
                    'midtrans_order_id' => $orderId,
                    'midtrans_snap_token' => $snapToken,
                    'status' => 'pending_verification',
                ]);

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_id' => $orderId
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Midtrans Savings Snap Error: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mendapatkan token: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function midtransNotification(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        \Illuminate\Support\Facades\Log::info('Midtrans Notification Received:', (array) $notification);

        if (!$notification) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Midtrans gross_amount can be a string like "150000.00", we must use the exact string from notification for signature
        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if ($notification->signature_key != $validSignatureKey) {
            \Illuminate\Support\Facades\Log::error('Midtrans Invalid Signature. Expected: ' . $validSignatureKey . ' Got: ' . $notification->signature_key);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;

        if (str_starts_with($orderId, 'INV-')) {
            $invoices = Invoice::where('midtrans_order_id', $orderId)->get();
            \Illuminate\Support\Facades\Log::info('Found Invoices for Order ID ' . $orderId . ': ' . $invoices->count());

            if ($transaction == 'capture' || $transaction == 'settlement') {
                if ($transaction == 'capture' && $type == 'credit_card' && $fraud == 'challenge') {
                    $invoices->each->update(['status' => 'pending_verification']);
                    \Illuminate\Support\Facades\Log::info('Transaction challenged (credit card).');
                } else {
                    foreach ($invoices as $invoice) {
                        if ($invoice->status != 'paid') {
                            $invoice->update([
                                'status' => 'paid', 
                                'payment_date' => now(),
                                'proof_path' => null // Clear if any previous manual attempt
                            ]);
                            
                            // Record to general transaction table
                            $lastTransaction = \App\Models\Transaction::orderBy('id', 'desc')->first();
                            $newBalance = ($lastTransaction ? $lastTransaction->balance : 0) + $invoice->amount;

                            \App\Models\Transaction::create([
                                'date' => now(),
                                'description' => 'Pembayaran Midtrans IPL - ' . ($invoice->resident->name ?? 'Warga') . ' (' . $invoice->period . ')',
                                'type' => 'credit',
                                'amount' => $invoice->amount,
                                'balance' => $newBalance,
                            ]);
                            \Illuminate\Support\Facades\Log::info('Invoice ID ' . $invoice->id . ' marked as PAID and recorded to Transactions.');
                        }
                    }
                }
            } elseif ($transaction == 'pending') {
                $invoices->each->update(['status' => 'pending_verification']);
                \Illuminate\Support\Facades\Log::info('Transaction pending.');
            } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $invoices->each->update(['status' => 'unpaid']);
                \Illuminate\Support\Facades\Log::info('Transaction failed/expired/cancelled.');
            }
        } elseif (str_starts_with($orderId, 'SAV-')) {
            $savingsTrx = SavingsTransaction::where('midtrans_order_id', $orderId)->first();
            if ($savingsTrx) {
                if ($transaction == 'capture' || $transaction == 'settlement') {
                    if ($transaction == 'capture' && $type == 'credit_card' && $fraud == 'challenge') {
                        $savingsTrx->update(['status' => 'pending_verification']);
                    } else {
                        if ($savingsTrx->status != 'success') {
                            $savingsTrx->update(['status' => 'success']);
                            
                            // Update collected amount in program
                            $program = $savingsTrx->program;
                            $program->collected_amount += $savingsTrx->amount;
                            $program->save();
                            
                            \Illuminate\Support\Facades\Log::info('Savings Transaction ID ' . $savingsTrx->id . ' marked as SUCCESS.');
                        }
                    }
                } elseif ($transaction == 'pending') {
                    $savingsTrx->update(['status' => 'pending_verification']);
                } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                    $savingsTrx->update(['status' => 'rejected']);
                }
            }
        }

        return response()->json(['message' => 'Notification processed']);
    }

    public function checkPaymentStatus($orderId)
    {
        try {
            $status = MidtransTransaction::status($orderId);
            $transaction = $status->transaction_status;
            
            $invoices = Invoice::where('midtrans_order_id', $orderId)->get();

            if ($transaction == 'capture' || $transaction == 'settlement') {
                foreach ($invoices as $invoice) {
                    if ($invoice->status != 'paid') {
                        $invoice->update([
                            'status' => 'paid',
                            'payment_date' => now(),
                        ]);

                        // Record to general transaction table
                        $lastTransaction = \App\Models\Transaction::orderBy('id', 'desc')->first();
                        $newBalance = ($lastTransaction ? $lastTransaction->balance : 0) + $invoice->amount;

                        \App\Models\Transaction::create([
                            'date' => now(),
                            'description' => 'Pembayaran Midtrans (Manual Check) - ' . ($invoice->resident->name ?? 'Warga') . ' (' . $invoice->period . ')',
                            'type' => 'credit',
                            'amount' => $invoice->amount,
                            'balance' => $newBalance,
                        ]);
                    }
                }
                return back()->with('success', 'Pembayaran berhasil dikonfirmasi dan status diperbarui!');
            } elseif ($transaction == 'pending') {
                return back()->with('info', 'Pembayaran masih menunggu (Pending). Silakan selesaikan pembayaran Anda.');
            } else {
                return back()->with('error', 'Status pembayaran: ' . $transaction);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengecek status: ' . $e->getMessage());
        }
    }
}
