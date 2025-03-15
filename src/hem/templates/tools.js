  var merge_form_name = "merge_form";
  var merged_findings_array_name = "findings";

  // depreacted 
  function addTextLine(id, text, table, form, remove_text, is_image)
   {
      var tableObject = document.getElementById(table);

      var rowObject = document.createElement("tr");
      rowObject.setAttribute("id", "row" + id);
      var textColObject = document.createElement("td");
      var linkColObject = document.createElement("td");
      linkColObject.setAttribute("class", "operations");
      var textObject = document.createTextNode(text);

      var linkObject = document.createElement("a");
      linkObject.setAttribute("href", "javascript:removeElement('" + id + "','" + table + "','" + form + "')");

      if(is_image)
      {
          removeNode = document.createElement("img");
	  removeNode.setAttribute("src", remove_text);
      }
      else
      {
          removeNode = document.createTextNode(remove_text); 
      }
      
      linkObject.appendChild(removeNode);
      textColObject.appendChild(textObject);
      linkColObject.appendChild(linkObject);
    
      rowObject.appendChild(textColObject);
      rowObject.appendChild(linkColObject);

      document.getElementById(table).appendChild(rowObject);
   }

   function appendTextToObject(obj_id, text)
   {
      document.getElementById(obj_id).value += text;
   }


   // deprecated
   function addElement(id, text, table, form, remove_text, is_image, array_name)
   {
      if(!document.getElementById(id))
      {
         var inputObject = document.createElement("input");
   	 
	 inputObject.setAttribute("name", array_name + "[" + id + "]");
 	 inputObject.setAttribute("value", text);
   	 inputObject.setAttribute("type", "hidden");
   	 inputObject.setAttribute("id", id);
   
         document.getElementById(form).appendChild(inputObject);

	 addTextLine(id, text, table, form, remove_text, is_image);
      }
   }

   // deprecated
   function removeTextLine(id, table, form)
   {
      var rowObject = document.getElementById("row" + id);

      document.getElementById(table).removeChild(rowObject);
   }

   // deprecated
   function removeElement(id, table, form)
   {
      if(document.getElementById(id))
      {
   
         var elementObject = document.getElementById(id);
         elementObject.setAttribute("name", "0");

         document.getElementById(form).removeChild(elementObject);
         removeTextLine(id, table, form);
      }
   }


   function attachFinding(id, detach_label, append_label)
   {
	// Change CSS class to selected
   	document.getElementById("row_" + id).setAttribute('class', 'selected');
	
	// Get the Row-Object via ID
	var row_object_ = document.getElementById("row_" + id);

	// Get all table cell child nodes
	var td_child_nodes_ = getChildNodesByTagName(row_object_, "TD");
	// Get all link child nodes of the first table cell
	var a_child_nodes_ = getChildNodesByTagName(td_child_nodes_[0], "A");

	// get the "append" link object 
	var append_link_child_node_ = a_child_nodes_[0];

	// Change the action of the append link
	append_link_child_node_.setAttribute("href", "javascript:detachFinding('" + id + "', '"+ detach_label + "', '" + append_label + "')");
	// Change the tooltip text
	append_link_child_node_.setAttribute("onmouseover", "tooltipLink('<pre>" + detach_label + "</pre>', ''); return true;");


	// Get the IMG tag of the append link
	var append_link_image_child_nodes_ = getChildNodesByTagName(append_link_child_node_, "IMG");
	var append_link_image_child_node_ = append_link_image_child_nodes_[0];

	// Change the image to the "detach" icon
	append_link_image_child_node_.setAttribute("src", "templates/icons/button_detach.png");

	// add the hidden form element for this finding
	addHiddenFormElement(merge_form_name, merged_findings_array_name, "1", id);

//	var text = "";

//        for (var i = 0; i < a_child_nodes_.length; i++)
//	    {
//		text = text + "Name: " + a_child_nodes_[i].nodeName + " Type: " + a_child_nodes_[i].nodeType + "\n";
//	    }

//	alert(text);
   }


   function detachFinding(id, detach_label, append_label)
   {
   	document.getElementById("row_" + id).setAttribute('class', original_row_classes_[id]);

	var row_object_ = document.getElementById("row_" + id);

	var td_child_nodes_ = getChildNodesByTagName(row_object_, "TD");
	var a_child_nodes_ = getChildNodesByTagName(td_child_nodes_[0], "A");

	var append_link_child_node_ = a_child_nodes_[0];

	append_link_child_node_.setAttribute("href", "javascript:attachFinding('" + id + "', '" + detach_label + "', '" + append_label + "')");
	append_link_child_node_.setAttribute("onmouseover", "tooltipLink('<pre>" + append_label + "</pre>', ''); return true;");


	var append_link_image_child_nodes_ = getChildNodesByTagName(append_link_child_node_, "IMG");
	var append_link_image_child_node_ = append_link_image_child_nodes_[0];

	append_link_image_child_node_.setAttribute("src", "templates/icons/button_attach.png");

	removeHiddenFormElement(merge_form_name, id);

   }

   function addHiddenFormElement(form, array_name, value, id)
   {
         var inputObject = document.createElement("input");
   	 
	 inputObject.setAttribute("name", array_name + "[" + id + "]");
 	 inputObject.setAttribute("value", value);
   	 inputObject.setAttribute("type", "hidden");
   	 inputObject.setAttribute("id", "field_" + id);
   
         document.getElementById(form).appendChild(inputObject);
   }



   function removeHiddenFormElement(form, id)
   {
	// 
         var fieldObject = document.getElementById("field_" + id);
         fieldObject.setAttribute("name", "0");

         document.getElementById(form).removeChild(fieldObject);	
   }


   function getChildNodesByTagName(node_object, tag_name)
   {
	var no_childnodes_ = node_object.childNodes.length;

	var child_objects_ = new Array();
	var child_counter_ = 0;

	var text = "";

        for (var i = 0; i < no_childnodes_; i++)
	    {
		text = text + "Name: " + node_object.childNodes[i].nodeName + " Type: " + node_object.childNodes[i].nodeType + "\n";
		if(node_object.childNodes[i].nodeName == tag_name)
		{
			child_objects_[child_counter_] = node_object.childNodes[i];
			child_counter_++;
		}
	    }
	   
	return child_objects_;
   }


