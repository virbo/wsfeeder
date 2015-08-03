<select class="js-example-basic-single js-states form-control" id="js_contoh"></select>
<script type="text/javascript">
  $(document).ready(function() {
    $(".js-example-basic-single").select2({
        ajax: {
            url: "<?php echo base_url();?>index.php/ws_nilai/form_kelas",
            dataType: 'json',
            delay: 250,
            data: function (term) {
                return {
                    q: term, // search term
                    page_limit: 10,
                   //page: page, // page number
                };
            },
            results: function (data) {
                return {
                    results: data
                };
                /*return {
                    results: id: data.id_smt, text: data.nm_smt
                };*/
               /*
               var results = [];
                $.each(data, function(index, item){
                    results.push({
                        id: item.id_smt,
                        text: item.nm_smt
                    });
                });
                return { results: results };*/
            },
            cache: true
        },
    });
   });
   
   
</script>
<!--script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script-->