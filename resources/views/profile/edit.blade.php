<x-app-layout>
    <!-- HERO SECTION -->
    <section class="relative bg-primary pt-10 pb-16 overflow-hidden">
        <!-- Abstract Background Ornaments -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -ml-20 -mt-20"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-black/10 rounded-full blur-2xl -mb-10 -mr-10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] pointer-events-none"></div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-montserrat text-2xl md:text-3xl font-bold text-white mb-2 tracking-tight">
                Pengaturan <span class="text-white/80">Akun</span>
            </h1>
            <p class="font-inter text-[14px] text-white/90 max-w-2xl">
                Kelola informasi data diri, preferensi keamanan, dan dokumen verifikasi Anda di sini.
            </p>
        </div>
    </section>

    <!-- WRAPPER TABS (Menggunakan Alpine.js) -->
    <div x-data="{ 
            activeTab: '{{ $errors->userDeletion->isNotEmpty() ? 'delete' : ($errors->updatePassword->isNotEmpty() ? 'password' : 'profile') }}',
            openDeleteModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}
         }" 
         class="relative -mt-8 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20 flex flex-col md:flex-row gap-6">
        
        <!-- SIDEBAR MENU TABS -->
        <div class="w-full md:w-1/3 lg:w-1/4 shrink-0">
            <div class="bg-surface border border-outline-variant/40 rounded-2xl p-3 premium-shadow flex flex-col gap-1 sticky top-24">
                
                <button @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'bg-primary text-white shadow-md' : 'text-on-surface hover:bg-surface-container hover:text-primary'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 text-left font-inter font-semibold text-[14px]">
                    <span class="material-symbols-outlined text-[20px]" :class="activeTab === 'profile' ? 'icon-fill' : ''">person</span>
                    Profil & Dokumen
                </button>

                <button @click="activeTab = 'password'" 
                        :class="activeTab === 'password' ? 'bg-primary text-white shadow-md' : 'text-on-surface hover:bg-surface-container hover:text-primary'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 text-left font-inter font-semibold text-[14px]">
                    <span class="material-symbols-outlined text-[20px]" :class="activeTab === 'password' ? 'icon-fill' : ''">lock</span>
                    Keamanan Password
                </button>

                <div class="h-px bg-outline-variant/30 my-2 mx-2"></div>

                <button @click="activeTab = 'delete'" 
                        :class="activeTab === 'delete' ? 'bg-red-600 text-white shadow-md' : 'text-red-600 hover:bg-red-50'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 text-left font-inter font-semibold text-[14px]">
                    <span class="material-symbols-outlined text-[20px]" :class="activeTab === 'delete' ? 'icon-fill' : ''">warning</span>
                    Hapus Akun
                </button>
            </div>
        </div>

        <!-- CONTENT PANELS -->
        <div class="w-full md:w-2/3 lg:w-3/4">
            
            <!-- ============================================== -->
            <!-- TAB 1: PROFIL & DOKUMEN -->
            <!-- ============================================== -->
            <div x-show="activeTab === 'profile'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="bg-surface rounded-2xl p-6 md:p-8 shadow-xl border border-outline-variant/30 premium-shadow" style="display: none;">
                
                <header>
                    <h2 class="text-xl font-montserrat font-bold text-on-surface mb-1">
                        {{ __('Informasi Profil & Dokumen') }}
                    </h2>
                    <p class="text-[14px] font-inter text-on-surface-variant">
                        {{ __('Perbarui informasi data diri, alamat email, dan dokumen verifikasi Anda.') }}
                    </p>
                </header>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <!-- Foto Profil -->
                    <div class="flex items-center gap-6 mb-8 bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/30">
                        <div class="shrink-0 relative group">
                            <img id="preview_image" class="h-20 w-20 object-cover rounded-full border-2 border-primary/20 shadow-sm" src="{{ $user->display_picture ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=e4dfff&color=140067' }}" alt="Foto Profil" />
                            <label for="profile_picture" class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                                <span class="material-symbols-outlined text-white text-[20px]">photo_camera</span>
                            </label>
                        </div>
                        <div>
                            <label for="profile_picture" class="font-inter font-semibold text-[14px] text-primary cursor-pointer hover:underline transition-colors block mb-1">
                                Ubah Foto Profil
                            </label>
                            <input id="profile_picture" name="profile_picture" type="file" class="hidden" accept="image/*" onchange="previewImage(event)" />
                            <p class="text-[12px] text-on-surface-variant font-inter">Format JPG, JPEG, atau PNG. Maksimal ukuran 2MB.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                        </div>
                    </div>

                    <!-- Input Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-inter font-semibold text-[13px]" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20 text-[14px]" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" class="font-inter font-semibold text-[13px]" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20 text-[14px]" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2 bg-amber-50 border border-amber-200 p-3 rounded-lg">
                                    <p class="text-[12px] text-amber-800 font-inter">
                                        {{ __('Alamat email Anda belum diverifikasi.') }}
                                        <button form="send-verification" class="underline font-bold hover:text-amber-600 rounded-md focus:outline-none">
                                            {{ __('Kirim ulang verifikasi.') }}
                                        </button>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-input-label for="phone_number" :value="__('Nomor WhatsApp')" class="font-inter font-semibold text-[13px]" />
                            <div class="relative mt-1">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[18px]">call</span>
                                <x-text-input id="phone_number" name="phone_number" type="text" class="block w-full pl-9 bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20 text-[14px]" :value="old('phone_number', $user->phone_number)" placeholder="08..." />
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                        </div>

                        <div>
                            <x-input-label for="nik" :value="__('NIK (Nomor Induk Kependudukan)')" class="font-inter font-semibold text-[13px]" />
                            <div class="relative mt-1">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[18px]">badge</span>
                                <x-text-input id="nik" name="nik" type="text" class="block w-full pl-9 bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20 text-[14px]" :value="old('nik', $user->nik)" placeholder="16 digit angka KTP" />
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('nik')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Alamat Lengkap (Domisili)')" class="font-inter font-semibold text-[13px]" />
                        <textarea id="address" name="address" rows="3" class="mt-1 block w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20 text-[14px] resize-none" placeholder="Masukkan alamat lengkap RT/RW, Kelurahan, Kecamatan...">{{ old('address', $user->address) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <!-- Dokumen Pribadi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-outline-variant/30 mt-6">
                        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/40">
                            <x-input-label for="ktp_image" :value="__('Dokumen KTP (Fisik)')" class="font-inter font-bold text-[14px] mb-3 block" />
                            @if($user->ktp_image_url)
                                <div class="mb-3 flex items-center gap-1.5 text-forest-green bg-forest-light px-3 py-1.5 rounded-lg border border-forest-green/20 w-fit">
                                    <span class="material-symbols-outlined text-[16px] icon-fill">check_circle</span>
                                    <span class="text-[12px] font-semibold">KTP Tersimpan</span>
                                </div>
                            @endif
                            <input id="ktp_image" name="ktp_image" type="file" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[12px] file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer transition-colors" accept="image/*" />
                            <x-input-error class="mt-2" :messages="$errors->get('ktp_image')" />
                        </div>

                        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/40">
                            <x-input-label for="sim_image" :value="__('Dokumen SIM A (Fisik)')" class="font-inter font-bold text-[14px] mb-3 block" />
                            @if($user->sim_image_url)
                                <div class="mb-3 flex items-center gap-1.5 text-forest-green bg-forest-light px-3 py-1.5 rounded-lg border border-forest-green/20 w-fit">
                                    <span class="material-symbols-outlined text-[16px] icon-fill">check_circle</span>
                                    <span class="text-[12px] font-semibold">SIM Tersimpan</span>
                                </div>
                            @endif
                            <input id="sim_image" name="sim_image" type="file" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[12px] file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer transition-colors" accept="image/*" />
                            <x-input-error class="mt-2" :messages="$errors->get('sim_image')" />
                        </div>
                    </div>

                    <!-- Tombol Simpan Profil -->
                    <div class="flex items-center gap-4 pt-6 mt-6 border-t border-outline-variant/30">
                        <button type="submit" class="bg-primary text-white font-inter font-bold text-[14px] py-2.5 px-6 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-md shadow-primary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            {{ __('Simpan Perubahan') }}
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="text-[13px] text-forest-green font-inter font-bold flex items-center gap-1.5 bg-forest-light px-3 py-1.5 rounded-lg border border-forest-green/20">
                                <span class="material-symbols-outlined text-[18px] icon-fill">check_circle</span>
                                {{ __('Profil Berhasil Diperbarui.') }}
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- ============================================== -->
            <!-- TAB 2: UPDATE PASSWORD -->
            <!-- ============================================== -->
            <div x-show="activeTab === 'password'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="bg-surface rounded-2xl p-6 md:p-8 shadow-xl border border-outline-variant/30 premium-shadow" style="display: none;">
                
                <header>
                    <h2 class="text-xl font-montserrat font-bold text-on-surface mb-1 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">security</span>
                        {{ __('Keamanan Password') }}
                    </h2>
                    <p class="text-[14px] font-inter text-on-surface-variant">
                        {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang, acak, dan unik agar tetap aman.') }}
                    </p>
                </header>

                <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6 max-w-xl">
                    @csrf
                    @method('put')

                    <div>
                        <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="font-inter font-semibold text-[13px]" />
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[18px]">key</span>
                            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full pl-9 bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20 text-[14px]" autocomplete="current-password" placeholder="Masukkan password lama" />
                        </div>
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password" :value="__('Password Baru')" class="font-inter font-semibold text-[13px]" />
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[18px]">lock_reset</span>
                            <x-text-input id="update_password_password" name="password" type="password" class="block w-full pl-9 bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20 text-[14px]" autocomplete="new-password" placeholder="Buat password baru" />
                        </div>
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password Baru')" class="font-inter font-semibold text-[13px]" />
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[18px]">password</span>
                            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full pl-9 bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20 text-[14px]" autocomplete="new-password" placeholder="Ulangi password baru" />
                        </div>
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-outline-variant/30">
                        <button type="submit" class="bg-primary text-white font-inter font-bold text-[14px] py-2.5 px-6 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-md shadow-primary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            {{ __('Update Password') }}
                        </button>

                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="text-[13px] text-forest-green font-inter font-bold flex items-center gap-1.5 bg-forest-light px-3 py-1.5 rounded-lg border border-forest-green/20">
                                <span class="material-symbols-outlined text-[18px] icon-fill">check_circle</span>
                                {{ __('Password berhasil diubah.') }}
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- ============================================== -->
            <!-- TAB 3: DELETE ACCOUNT -->
            <!-- ============================================== -->
            <div x-show="activeTab === 'delete'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="bg-red-50/50 rounded-2xl p-6 md:p-8 shadow-xl border border-red-200 premium-shadow" style="display: none;">
                
                <header class="mb-6">
                    <h2 class="text-xl font-montserrat font-bold text-red-600 mb-1 flex items-center gap-2">
                        <span class="material-symbols-outlined text-red-600">gpp_bad</span>
                        {{ __('Hapus Akun Permanen') }}
                    </h2>
                    <p class="text-[14px] font-inter text-on-surface-variant leading-relaxed">
                        {{ __('Peringatan: Jika akun Anda dihapus, seluruh data, riwayat transaksi, dan dokumen Anda akan dihapus secara permanen dari server KlikRental. Proses ini tidak dapat dibatalkan.') }}
                    </p>
                </header>

                <div class="p-5 bg-red-100 border border-red-200 rounded-xl mb-6">
                    <p class="font-inter text-[13px] text-red-700 font-semibold flex items-start gap-2">
                        <span class="material-symbols-outlined text-[18px]">info</span>
                        Pastikan Anda telah menyelesaikan semua kewajiban sewa dan tagihan sebelum menghapus akun.
                    </p>
                </div>

                <button @click="openDeleteModal = true" class="bg-red-600 text-white font-inter font-bold text-[14px] py-2.5 px-6 rounded-xl hover:bg-red-700 transition-all active:scale-95 shadow-md shadow-red-600/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                    {{ __('Hapus Akun Saya') }}
                </button>
            </div>

        </div>

        <!-- ============================================== -->
        <!-- MODAL POPUP HAPUS AKUN KUSTOM -->
        <!-- ============================================== -->
        <div x-show="openDeleteModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
            
            <!-- Backdrop Transparan Hitam -->
            <div x-show="openDeleteModal" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="openDeleteModal = false"></div>
            
            <!-- Modal Box -->
            <div x-show="openDeleteModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="relative bg-surface w-full max-w-lg rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-red-200">
                
                <div class="p-5 border-b border-red-200 flex justify-between items-center bg-red-50">
                    <h3 class="font-montserrat font-bold text-lg text-red-600 flex items-center gap-2">
                        <span class="material-symbols-outlined">warning</span> Konfirmasi Penghapusan
                    </h3>
                    <button @click="openDeleteModal = false" type="button" class="text-on-surface-variant hover:text-red-600 transition-all">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

                    <p class="font-inter text-[14px] text-on-surface-variant leading-relaxed mb-5">
                        {{ __('Sekali dihapus, akun Anda dan semua datanya tidak bisa dikembalikan. Silakan masukkan password Anda untuk mengonfirmasi tindakan ini.') }}
                    </p>

                    <div>
                        <x-input-label for="password" value="{{ __('Password Saat Ini') }}" class="font-inter font-semibold text-[13px] text-on-surface" />
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[18px]">password</span>
                            <x-text-input id="password" name="password" type="password" class="block w-full pl-9 bg-surface border-outline-variant/60 rounded-xl focus:border-red-500 focus:ring-red-500/20 text-[14px]" placeholder="{{ __('Masukkan password...') }}" autofocus />
                        </div>
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600" />
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="openDeleteModal = false" class="bg-surface-container text-on-surface-variant font-inter font-bold text-[14px] py-2.5 px-5 rounded-xl hover:text-on-surface border border-outline-variant/50 transition-colors">
                            {{ __('Batal') }}
                        </button>

                        <button type="submit" class="bg-red-600 text-white font-inter font-bold text-[14px] py-2.5 px-6 rounded-xl hover:bg-red-700 transition-all active:scale-95 shadow-md shadow-red-600/20">
                            {{ __('Hapus Permanen') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Script Preview Gambar Profil -->
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('preview_image');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>