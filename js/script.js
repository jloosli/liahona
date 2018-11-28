if (/liahona\.loc/.test(window.location.hostname)) {
  $(document).ready(function () {
    $('img').on("error", function () {
      let src = $(this).attr('src');
      if (/liahona\.loc/.test(src)) {
        let newsrc = src.replace('liahona.loc', 'theliahonaproject.net');
        $(this).attr('src', newsrc);
      } else if (src[0] === '/') {
        let newsrc = 'https://theliahonaproject.net' + src;
        $(this).attr('src', newsrc);
      }
    });
  })
}