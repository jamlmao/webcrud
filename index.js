function loadChartData(){
		
    var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var rawData = JSON.parse(this.responseText);
      var labels = [];
      var data = [];
      for (let i = 0; i < rawData.length; i++) {
        labels.push(rawData[i]["status_"]);
        data.push(rawData[i]["num"]);
      }

      const pie = document.getElementById('myChart');

      new Chart(pie, {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            label: 'Car Status',
            data: data,
            borderWidth: 1
          }]
        },
        options: {
          title: {
            display: true,
            text: 'Car Status Chart'
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
  };

  xhttp.open("GET", "carstatus_pie.php", true);
  xhttp.send();

  var xhttp2 = new XMLHttpRequest();
  xhttp2.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var rawData = JSON.parse(this.responseText);
      var labels = [];
      var data = [];
      for (let i = 0; i < rawData.length; i++) {
        labels.push(rawData[i]["model"]); 
        data.push(rawData[i]["num"]);
      }

      const pie2 = document.getElementById('myChart2');

      new Chart(pie2, {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            label: 'Number of rentals',
            data: data,
            borderWidth: 1
          }]
        },
        options: {
          title: {
            display: true,
            text: 'Number of rentals '
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
  };

  xhttp2.open("GET", "car_rented_pie.php", true);
  xhttp2.send();


}







