<div class="row">
    <div class="col-12">
    
    <div class="row">
        <div class="col-lg-12">
            {{$soal->links('vendor.pagination.bootstrap-5')}}
              
        </div>
       
    </div>

    @if ($soal->isNotEmpty())
        @foreach ($soal as $item)
            <div class="card">
                <div class="card-header">
                    Soal No. {{$this->page}}
                </div>
                <div class="card-body">
                    <h3 style="line-height: 1.8;">{!! $item->soal !!}</h3>
                    <br/>
                    @if ($item->gambar<>'')
                    <img src="{{ url($item->gambar) }}" class="img-responsive" width="100%">
                    @endif
           
                </div>
            </div>
            <br/>
            <h3>Klik Pada Pilihan Jawaban yang Benar !</h3>
            <div wire:click="pilih('pilihan_a', '{{$this->page}}')" class="card pointer {{($pilih[$this->page-1] == 'pilihan_a') ? 'border border-primary' : ''}}">
                <div class="card-header">
                    Pilihan A
                </div>
                <div class="card-body">
                    <h4>{!! $item->pilihan_a !!}</h4>
                </div>
            </div>
            <br/>
            <div wire:click="pilih('pilihan_b', '{{$this->page}}')" class="card pointer {{($pilih[$this->page-1] == 'pilihan_b') ? 'border border-primary' : ''}}">
                <div class="card-header">
                    Pilihan B
                </div>
                <div class="card-body">
                    <h4>{!! $item->pilihan_b !!}</h4>
                </div>
            </div>
            <br/>
            <div wire:click="pilih('pilihan_c', '{{$this->page}}')" class="card pointer {{($pilih[$this->page-1] == 'pilihan_c') ? 'border border-primary' : ''}}">
                <div class="card-header">
                    Pilihan C
                </div>
                <div class="card-body">
                    <h4>{!! $item->pilihan_c !!}</h4>
                </div>
            </div>
            <br/>
            <div wire:click="pilih('pilihan_d', '{{$this->page}}')" class="card pointer {{($pilih[$this->page-1] == 'pilihan_d') ? 'border border-primary' : ''}}">
                <div class="card-header">
                    Pilihan D
                </div>
                <div class="card-body">
                    <h4>{!! $item->pilihan_d !!}</h4>
                </div>
            </div>
            <br/>
            <div wire:click="pilih('pilihan_e', '{{$this->page}}')" class="card pointer {{($pilih[$this->page-1] == 'pilihan_e') ? 'border border-primary' : ''}}">
                <div class="card-header">
                    Pilihan E
                </div>
                <div class="card-body">
                    <h4>{!! $item->pilihan_e !!}</h4>
                </div>
            </div>
        @endforeach

        <div class="row mt-3">
            <div class="col-md-10">
                {{$soal->links()}}
            </div>
            <div class="col-md-2">
                <button id="saveBtn" class="btn btn-sm btn-primary float-right">Selesai Mengerjakan</button>
            </div>
        </div>
        
    @else
        <div class="alert alert-danger" role="alert">
        Quiz tidak memiliki soal
        </div>
    @endif

    </div>
</div>

@push('page-script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tetapkan tanggal kita menghitung mundur
    var countDownDate = new Date("{{ session('waktu_akhir') }}").getTime();
    
    // Perbarui hitungan mundur setiap 1 detik
    var x = setInterval(function() {
    
      // Dapatkan tanggal dan waktu hari ini
      var now = new Date().getTime();
        
      // Temukan jarak antara sekarang dan tanggal hitung mundur
      var distance = countDownDate - now;
        
      // Perhitungan waktu untuk hari, jam, menit dan detik
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
      // Keluarkan hasil dalam elemen dengan id = "demo"
      document.getElementById("timer").innerHTML = minutes + "m " + seconds + "s ";
        
      //Jika hitungan mundur selesai, tulis beberapa teks
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("timer").innerHTML = "Waktu Habis";
        Swal.fire({
                title: 'Waktu Ujian Sudah Habis',
                icon: 'warning',
                confirmButtonText: 'Oke',
                allowOutsideClick: false,
                allowEscapeKey: false
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    @this.call('selesai');
                    Swal.fire(
                                'Jawaban Soal Ujian Berhasil Dikirimkan',
                                'Silahkan klik tombol OK',
                                'success'
                            ).then(function (result) {
                            if (result.value) {
                                if ("{{ session('online') }}"=='1')
                                {
                                    window.location = "maba/dashboard";
                                }else
                                {
                                    window.location = "ujian";
                                }
                                
                            }
                        })
                } else if (result.isDenied) {
                    Swal.fire('Lanjutkan Ujian', '', 'info')
                }
})

      }
    }, 1000);
    </script>

<script>
    $('#saveBtn').click(function (e) {
          e.preventDefault();
          $('#saveBtn').html('Mengirim Data ..');

          Swal.fire({
                title: 'Apakah Ujian Sudah Selesai ?',
                showCancelButton: true,
                confirmButtonText: 'Iya',
                denyButtonText: `Tidak`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    clearInterval(x);
                    @this.call('selesai');
                    Swal.fire(
                                'Jawaban Soal Ujian Berhasil Dikirimkan',
                                'Silahkan klik tombol OK',
                                'success'
                            ).then(function (result) {
                            if (result.value) {
                                if ("{{ session('online') }}"=='1')
                                {
                                    window.location = "maba/dashboard";
                                }else
                                {
                                    window.location = "ujian";
                                }
                            }
                        })
                } else if (result.isDenied) {
                    Swal.fire('Lanjutkan Ujian', '', 'info')
                }
})

          
        });
</script>



@endpush
