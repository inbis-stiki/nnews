<div class="py-2">
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <div class="card text-white mb-3 bg-success">
          <div class="card-body">
            <p class="display-4 counter" data-count="<?php echo $stats['views'] ?>"></p>
            <div><i class="fa fa-eye"></i><p style="display: inline-block">&ensp;Dilihat</p></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white mb-3 bg-warning">
          <div class="card-body text-dark">
            <p class="display-4 counter" data-count="<?php echo $stats['news'] ?>"></p>
            <div><i class="fa fa-globe"></i><p style="display: inline-block">&ensp;Berita & Artikel</p></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white mb-3 bg-dark">
          <div class="card-body">
            <p class="display-4 counter" data-count="<?php echo $stats['gallery'] ?>"></p>
            <div><i class="fa fa-image"></i><p style="display: inline-block">&ensp;Galeri</p></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white mb-3 bg-primary">
          <div class="card-body">
            <p class="display-4 counter" data-count="<?php echo $stats['videos'] ?>"></p>
            <div><i class="fa fa-video-camera"></i><p style="display: inline-block">&ensp;Video</p></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white mb-3 bg-danger">
          <div class="card-body">
            <p class="display-4 counter" data-count="<?php echo $stats['users'] ?>"></p>
            <div><i class="fa fa-users"></i><p style="display: inline-block">&ensp;Pengguna</p></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white mb-3 bg-secondary">
          <div class="card-body">
            <p class="display-4 counter" data-count="<?php echo $stats['emagz'] ?>"></p>
            <div><i class="fa fa-file"></i><p style="display: inline-block">&ensp;E-Magazine</p></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white mb-3 bg-info">
          <div class="card-body">
            <p class="display-4 counter" data-count="<?php echo $stats['shares'] ?>"></p>
            <div><i class="fa fa-share-alt"></i><p style="display: inline-block">&ensp;Dibagikan</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="py-0">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class="">Paling Trending</h1>
        <div class="col-md-12" id="chart_div"> </div>
      </div>
      <div class="col-md-12">
        <h1 class="">Paling Disukai</h1>
        <div class="col-md-12" id="chart_like_div"> </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.counter').each(function() {
  var $this = $(this),
    countTo = $this.attr('data-count');

  $({ countNum: $this.text()}).animate({countNum: countTo}, {
    duration: 1000,
    easing:'linear',
    step: function() {$this.text(Math.floor(this.countNum));},
    complete: function() {$this.text(this.countNum);}
  });  
});
</script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawTrendingChart);
  google.charts.setOnLoadCallback(drawLikedChart);
  function drawTrendingChart() {
    var data = new google.visualization.arrayToDataTable([
      ['News', 'Trending'],
      <?php foreach($trending as $t){ ?>
      ['<?= limit_text($t->TITLE_NEWS) ?>', <?= $t->TRENDING ?>],
      <?php } ?>
    ]);
    var options = {height: 300, legend: {position: 'bottom'}};
    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }

  function drawLikedChart() {
    var data = new google.visualization.arrayToDataTable([
      ['News Title', 'Likes', { role: 'style' }],
      <?php foreach($likes as $t){ ?>
      ['<?= limit_text($t->TITLE_NEWS) ?>', <?= $t->LIKES ?>, 'red'],
      <?php } ?>
    ]);
    var options = {height: 300, legend: {position: 'bottom'}, colors: ['red']};
    var chart = new google.visualization.BarChart(document.getElementById('chart_like_div'));
    chart.draw(data, options);
  }
</script>
<?php 
function limit_text($text, $limit = 4) {
  if (str_word_count($text, 0) > $limit) {
    $words = str_word_count($text, 2);
    $pos = array_keys($words);
    $text = substr($text, 0, $pos[$limit]) . '...';
  }
  return $text;
}
?>