<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="font-montserrat text-2xl font-bold text-on-surface">Verifikasi Email</h2>
        <p class="font-inter text-sm text-on-surface-variant mt-2 leading-relaxed">
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan tautan yang baru.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 bg-forest-light text-forest-green border border-forest-green/20 text-sm p-3 rounded-xl font-inter text-center dark:text-[#8cd95c]">
            Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-primary text-[#FFFFFF] font-montserrat font-bold text-[14px] py-3 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-[13px] font-inter text-on-surface-variant hover:text-error transition-colors hover:underline">
                Keluar (Log Out)
            </button>
        </form>
    </div>
</x-guest-layout>