
document.observe('dom:loaded', function() {
               
    var inputElement = document.createElement("input");
            inputElement.setAttribute("type", "hidden");
            inputElement.setAttribute("name", "product[category_id_asc]");
            inputElement.setAttribute("id", "category_id_asc");
                    
    $('product_info_tabs_ascurl_content').appendChild(inputElement);
                 
})






