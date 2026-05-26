<section>
    <header>
        <h2 class="text-lg font-montserrat font-bold text-on-surface">
            {{ __('Informasi Profil & Dokumen') }}
        </h2>
        <p class="mt-1 text-sm font-inter text-on-surface-variant">
            {{ __('Perbarui informasi data diri, alamat email, dan dokumen verifikasi Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center gap-6 mb-8">
            <div class="shrink-0 relative group">
                <img id="preview_image" class="h-20 w-20 object-cover rounded-full border-2 border-primary/20 shadow-sm" src="{{ $user->display_picture }}" alt="Foto Profil" />
                <label for="profile_picture" class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                    <span class="material-symbols-outlined text-white text-[20px]">photo_camera</span>
                </label>
            </div>
            
            <div>
                <label for="profile_picture" class="font-inter font-semibold text-[13px] text-primary cursor-pointer hover:underline transition-colors block mb-1">
                    Ubah Foto Profil
                </label>
                <input id="profile_picture" name="profile_picture" type="file" class="hidden" accept="image/*" onchange="previewImage(event)" />
                <p class="text-[11px] text-on-surface-variant font-inter">JPG, JPEG, atau PNG. Maks 2MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="font-inter font-semibold text-[13px]" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="font-inter font-semibold text-[13px]" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-[12px] text-on-surface-variant font-inter">
                            {{ __('Alamat email Anda belum diverifikasi.') }}
                            <button form="send-verification" class="underline text-primary hover:text-primary/80 rounded-md focus:outline-none">
                                {{ __('Klik di sini untuk mengirim ulang email.') }}
                            </button>
                        </p>
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="phone_number" :value="__('Nomor WhatsApp')" class="font-inter font-semibold text-[13px]" />
                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" :value="old('phone_number', $user->phone_number)" placeholder="08..." />
                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
            </div>

            <div>
                <x-input-label for="nik" :value="__('NIK (Nomor Induk Kependudukan)')" class="font-inter font-semibold text-[13px]" />
                <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" :value="old('nik', $user->nik)" placeholder="16 digit angka" />
                <x-input-error class="mt-2" :messages="$errors->get('nik')" />
            </div>
        </div>

        <div>
            <x-input-label for="address" :value="__('Alamat Lengkap')" class="font-inter font-semibold text-[13px]" />
            <textarea id="address" name="address" rows="3" class="mt-1 block w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" placeholder="Alamat domisili saat ini...">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-outline-variant/30">
            <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/40">
                <x-input-label for="ktp_image" :value="__('Upload Foto KTP')" class="font-inter font-bold text-[13px] mb-2" />
                
                @if($user->ktp_image_url)
                    <div class="mb-3 flex items-center gap-1.5 text-forest-green bg-forest-light px-3 py-1.5 rounded-lg border border-forest-green/20 w-fit">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span class="text-[11px] font-semibold">KTP Tersimpan</span>
                    </div>
                @endif
                
                <input id="ktp_image" name="ktp_image" type="file" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" accept="image/*" />
                <x-input-error class="mt-2" :messages="$errors->get('ktp_image')" />
            </div>

            <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/40">
                <x-input-label for="sim_image" :value="__('Upload Foto SIM')" class="font-inter font-bold text-[13px] mb-2" />
                
                @if($user->sim_image_url)
                    <div class="mb-3 flex items-center gap-1.5 text-forest-green bg-forest-light px-3 py-1.5 rounded-lg border border-forest-green/20 w-fit">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span class="text-[11px] font-semibold">SIM Tersimpan</span>
                    </div>
                @endif

                <input id="sim_image" name="sim_image" type="file" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" accept="image/*" />
                <x-input-error class="mt-2" :messages="$errors->get('sim_image')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-outline-variant/30">
            <button type="submit" class="bg-primary text-[#FFFFFF] font-montserrat font-bold text-[13px] py-2.5 px-6 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[13px] text-forest-green font-inter font-bold flex items-center gap-1.5"
                >
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>

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