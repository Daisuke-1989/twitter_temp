var worker = new Worker('../js/worker.js');
let cardsData = [];
worker.addEventListener('message', function (e) {
  var jsonStr = e.data.slice(6);
  var jsonData = JSON.parse(jsonStr || "null");
  console.log(jsonData);
  cardsData.length = 0;
  // for (let i = 0; i < jsonData.length; i++) {
  //   const el = jsonData['value'];
  //   cardsData.push({ id: el, idCode: el, });
  // }
  jsonData.forEach(element => {
    let el = element.value;
    cardsData.push({ id: el, idCode: el, });
    // OCL.StructureView.showStructures('actstruct');
  });
  // const setFunc = async () => {
  //   await Promise.all(jsonData.map(async element => {
  //     let el = element.value;
  //     await cardsData.push({ id: el, idCode: el, })
  //   }))
  //   OCL.StructureView.showStructures('actstruct');
  // };
  // OCL.StructureView.showStructures('actstruct');
}, false);
