function initTranslate(urlString) {
  $.ajax({
//    url: '<?php echo $this->url(array('module' => 'translate', 'controller' => 'dict'), 'defaultlang') ?>',
    url: urlString,
    success: function(data) {
      $.jsperanto.init(function(t) {}, {
        interpolationPrefix: '%',
        interpolationSuffix: '%',
        dictionary: data
      });
    },
    error: function() {
      $.t = function(key, options) {
        return key;
      }
    },
    async: false
  });
}
