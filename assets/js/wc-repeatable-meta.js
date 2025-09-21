jQuery(document).ready(function ($) {
  var rowIndex = $("#repeatable-fieldset-one").data("index");

  $(".add-documents").on("click", function (e) {
    e.preventDefault();
    console.log("clicked");
    var newRow = $(".repeatable.smdpicker_documents_field:first").clone();
    newRow.find("input").each(function () {
      $(this).val("");
      var name = $(this)
        .attr("name")
        .replace(/_0/, "_" + rowIndex);
      $(this).attr("name", name).attr("id", name);
    });
    newRow.insertBefore($(this));
    rowIndex++;
  });

  $(document).on("click", ".remove-document", function (e) {
    e.preventDefault();
    $(this).closest(".repeatable.smdpicker_documents_field").remove();
  });
});
