self.addEventListener('message', function (e) {
  self.importScripts("../../node_modules/openchemlib/dist/openchemlib-full.js");
  var data = e.data;
  switch (data.cmd) {
    case 'query':
      var db = fetch('./output.json').then(function (r) {
        response = r;
        return response.json();
      }).then(function (r) {
        // console.log("confirm");
        // console.log(r);
        db = r.molucules;
        var queryMol = OCL.Molecule.fromMolfile(data.msg + '');
        var targetMW = queryMol.getMolecularFormula().relativeWeight;
        // console.log(targetMW);
        var index = queryMol.getIndex();
        var targetID = queryMol.getIDCode();
        var intermediate = [];
        var result = [];
        var similarity;
        var length = db.length;
        // console.log(db);
        for (i = 0, ii = length; i < ii; i++) {
          if (db[i]['value'] === targetID) {
            similarity = 1e10;
          } else {
            similarity = OCL.SSSearcherWithIndex.getSimilarityTanimoto(index, db[i]['act_idx']) * 100000 - Math.abs(targetMW - db[i]['mw']) / 1000;
          }
          intermediate.push([db[i], similarity]);
        }
        intermediate.sort(function (a, b) {
          return b[1] - a[1];
        });
        for (i = 0, ii = intermediate.length; i < ii; i++) {
          result.push(intermediate[i][0]);
        }
        var molArray = result.slice(0, 8);
        console.log(result.slice(0, 8));

        self.postMessage('Data: ' + JSON.stringify(molArray));
        return db;
      }).catch(function (err) {
        // console.log(err);
        // console.log('Fetch problem: ' + err.message);
      });
  };
}, false);
