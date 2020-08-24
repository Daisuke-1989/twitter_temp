@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Update</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tweets.update', ['tweets' => $tweets]) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-0">
                            <div class="col-md-12 p-3 w-100 d-flex">
                                <img src="{{ asset('storage/profile_image/' .$user->profile_image) }}" class="rounded-circle" width="50" height="50">
                                <div class="ml-2 d-flex flex-column">
                                    <p class="mb-0">{{ $user->name }}</p>
                                    <a href="{{ url('users/' .$user->id) }}" class="text-secondary">{{ $user->screen_name }}</a>
                                </div>
                            </div>
                            <div id="jsme_container"></div>
                            <canvas id="canvas" data-idcode="{{ $tweets->idcode }}" width="200" height="100" class="actstruct" hidden></canvas>
                            <input id="idcode" type="text" name="idcode" hidden >
                            <div class="col-md-12">
                                <textarea class="form-control @error('text') is-invalid @enderror" name="text" required autocomplete="text" rows="4">{{ old('text') ? : $tweets->text }}</textarea>

                                @error('text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-right">
                                <p class="mb-4 text-danger">140文字以内</p>
                                <button type="submit" class="btn btn-primary">
                                    ツイートする
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('node_modules/jsme/jsme/jsme.nocache.js') }}"></script>
<script src="{{ asset('node_modules/openchemlib/dist/openchemlib-full.js') }}"></script>
<script>
    //this function will be called after the JavaScriptApplet code has been loaded.
    function jsmeOnLoad() {
      jsmeApplet = new JSApplet.JSME("jsme_container", "380px", "340px");
      function showSmiles(jsmeEvent) {
        var jsme = jsmeEvent.src;
        var molfile = jsme.molFile();
        var setMol = OCL.Molecule.fromMolfile(molfile + '');
        var setID = setMol.getIDCode();
        let idcode = document.getElementById('idcode');
        idcode.setAttribute("value", setID);
      }
      let canvasData = document.getElementById('canvas');
      let idCode = canvasData.getAttribute('data-idcode');
      let mol = OCL.Molecule.fromIDCode(idCode);
      let molFile = mol.toMolfile();
      jsmeApplet.readMolFile(molFile);
      jsmeApplet.setAfterStructureModifiedCallback(showSmiles);
    }
  </script>
@endsection
