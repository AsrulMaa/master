function createSlug() {
	let title = $('#title').val();
	$('#slug').val(string_to_slug(title));
}



function string_to_slug (str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();
  
    // remove accents, swap ñ for n, etc
    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to   = "aaaaeeeeiiiioooouuuunc------";
    for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
}

jQuery(document).ready(function() {
    $(document).on('keyup', '#title', function(event) {
        var link = string_to_slug($(this).val());
        var title = $(this).val().replace(/[^0-9a-z ]/gi, ' ').toLowerCase().replace(/ +/g, ' ').toLowerCase();

        $('.blog-slug').html(link);
        $('#title').val(title);
        $('#input-slug').val(string_to_slug(link));

    });

    $(document).on('focusout', '.blog-slug', function(event) {
          var link = $(this).html().replaceAll(/[^0-9a-z]/gi, '-').replaceAll(/-+/g, '-').toLowerCase();

          $('.blog-slug').html(link);
          $('#input-slug').html(link);
    });

   
});
