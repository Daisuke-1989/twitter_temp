@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create</div>
                <div class="card-body">
                    <div id="jsme_container"></div>
                    <div class="container">
                        <div class="section">
                            <div id="app" class="row columns is-multiline">
                                <div v-for="card in cardData" :key="card.id" class="column is-3">
                                    <div class="card large">
                                        <div class="card-image is-16by9">
                                            <canvas v-on:click="getData" :data-idcode="card.idCode" width="200" height="100" class="actstruct" ></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('tweets.store') }}">
                        @csrf
                        <div class="form-group row mb-0">
                            <div class="col-md-12 p-3 w-100 d-flex">
                                <img src="{{ asset('storage/profile_image/' .$user->profile_image) }}" class="rounded-circle" width="50" height="50">
                                <div class="ml-2 d-flex flex-column">
                                    <p class="mb-0">{{ $user->name }}</p>
                                    <a href="{{ url('users/' .$user->id) }}" class="text-secondary">{{ $user->name }}</a>
                                </div>
                            </div>
                            <div class="card-image is-16by9">
                                <canvas id="canvas" data-idcode="" width="200" height="100" class="actstruct"></canvas>
                            </div>
                            <input id="idcode" type="text" name="idcode" hidden >
                            <div class="col-md-12">
                                <textarea class="form-control @error('text') is-invalid @enderror" name="text" required autocomplete="text" rows="4">{{ old('text') }}</textarea>

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
<script src="{{ asset('js/cardsData.js') }}"></script>
<script>
    //this function will be called after the JavaScriptApplet code has been loaded.
    function jsmeOnLoad() {
      jsmeApplet = new JSApplet.JSME("jsme_container", "380px", "340px");
      function showSmiles(jsmeEvent) {
        var jsme = jsmeEvent.src;
        var molfile = jsme.molFile();
        // console.log(molfile);
        worker.postMessage({ 'cmd': 'query', 'msg': molfile });
      }
      jsmeApplet.setAfterStructureModifiedCallback(showSmiles);
    }
  </script>
  <script>
    var app = new Vue({
      el: '#app',
      created() {
      },
      watch: {
        cardData: function (newVal, oldVal) {
          console.log(newVal, oldVal)
          OCL.StructureView.showStructures('actstruct');
        }
      },
      data: {
        cardData: cardsData
      },
      methods: {
        getData: function (event) {
          let canvasData = document.getElementById('canvas');
          let idcode = document.getElementById('idcode');
          canvasData.setAttribute("data-idcode", event.target.dataset.idcode);
          idcode.setAttribute("value", event.target.dataset.idcode);
          OCL.StructureView.showStructures('actstruct');
        }
    }})
  </script>
@endsection
