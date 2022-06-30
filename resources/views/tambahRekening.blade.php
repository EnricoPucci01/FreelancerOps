@extends('header')
@section('content')
<center>
    <div class="card" style="width: 70%; margin-top: 20px;" >
        <div class="card-body">
          <h3 class="card-title">Tambah Nomor Rekening</h5>
          <form action={{url("/tambahRekening")}} method="POST">
            @csrf
            @method('POST')
              <table>
                        <!-- No Rek Tujuan -->
                        <tr>
                            <td>
                                <label for="no_rek" class="form-label">Nomor Rekening</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="number" name="no_rek" id='norek' class="form-control">
                            </td>
                        </tr>

                         <!-- Nama -->
                         <tr>
                            <td>
                                <label for="bank" class="form-label">Bank</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group mb-3">
                                    <select class="form-select" name='bank' id="bank" aria-label="Default select example">
                                        @foreach ($dataBank as $item)
                                            <option value={{$item['code']}}>{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                            </td>
                        </tr>

              </table>

              <button type="submit" class="btn btn-primary" style="margin-top: 10px" id='myBtn' >Tambah</button>
                {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
          </form>
        </div>
      </div>
</center>
@endsection
