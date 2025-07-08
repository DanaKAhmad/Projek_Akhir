@extends('layouts.template')


@section('content')
<div class="app-content-header py-3 mb-4 border-bottom">
    <div class="container-fluid">
        <h3 class="mb-0 fw-bold">Jam Kerja</h3>
    </div>
</div>

     <div class="container" >
      
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
      
            
        <form action="{{ route('admin.jamkerja.store')}}" method="POST">
            
            @csrf
            <div class="form-group">
                <label>Jam Masuk</label>
                <input type="time" name="jam_masuk" value="{{ $jamkerja->jam_masuk ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Jam Pulang</label>
                <input type="time" name="jam_pulang" value="{{ $jamkerja->jam_pulang ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="batas_terlambat">Batas Keterlambatan (Alpha Jika Lewat)</label>
                <input type="time" class="form-control" name="batas_terlambat" value="{{ $jamkerja->batas_terlambat ?? '' }}">
            </div>

           {{--}} <button type="submit" class="btn btn-primary">Atur Jam</button> --}}
           <button type="submit" class="btn btn-primary">
                {{ $jamkerja ? 'Update Jam Kerja' : 'Atur Jam Kerja' }}
            </button>
        </form>
        
        
   
    
        <!--edit-->
        {{--}}
        @if (request()->get('edit') == 'true')
        <h2> Edit Jam Kerja</h2>
        <form action="{{ route('admin.jamkerja.store')}}" method="POST">
            
            @csrf
            <div class="form-group">
                <label>Jam Masuk</label>
                <input type="time" name="jam_masuk" value="{{ old( $jamkerja->jam_masuk ?? '') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Jam Pulang</label>
                <input type="time" name="jam_pulang" value="{{ old( $jamkerja->jam_pulang ?? '') }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Atur Jam</button>
        </form>
        @endif 
        --}}

    </div>

    @if ($jamkerja)
    <hr>
         <table class="table table-bordered" style="text-align: center">
        <thead>
          <tr>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th> 
             <th>Batas Keterlambatan</th> 
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$jamkerja->jam_masuk}}</td>
                 <td>{{$jamkerja->jam_pulang}}</td>
                 <td>{{$jamkerja->batas_terlambat}}</td>
                 <td>
                   {{--}} <a href="{{ route('admin.jamkerja' ,['edit' => 'true']) }}" class="btn btn-sm btn-warning">Edit</a> --}}
                    <form action="{{ route('admin.jamkerja.destroy') }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus jam kerja?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>

                    
                 </td>
            </tr>
    </table>

    
    
    @endif
   



 </div>        



@endsection