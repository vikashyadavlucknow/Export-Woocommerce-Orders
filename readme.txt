if not worked change line 24
var selectedOrders = $('input[name="id[]"]:checked').map(function () {
to
var selectedOrders = $('input[name="post[]"]:checked').map(function () {