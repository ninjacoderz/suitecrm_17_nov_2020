<!-- dialog Customer Invoice Notes -->
    <div id = "dialog_customer_inv_note_pdf" hidden>
		<div id="edit_template_customer_inv_notes" >
			
			<h4 class="text-center">Template Customer Invoice Notes</h4>
			<div>
				<div class="label" >Select Template :</div>
				<select style="width:100%;margin-bottom:2px;" id="select_title_template_customer_inv_notes" >
					<option label="" value=""></option>
				</select>
				<div class="label"> Title : </div>
				<input style="width:100%;" id="title_custome_inv_template" name="title_custome_inv_template" type="text" value="" />
				<input id="id_customer_inv_template" hidden name="id_customer_inv_template" type="text" value="" />
			</div>
			<div>
				<div class="label" >Customer Invoice Notes :</div>
				<div class="input">
					<textarea id="content_customer_inv_template" style="width:100%;height:200px;">
					</textarea>
				</div>
			</div>
		</div>
	</div>
