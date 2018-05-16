	<div id="primary" class="content-area">
		<main id="main" class="site-main">
        
            <div class="inv-wrapper">
                <amp-state id="inventoryList" src="json/all.json"></amp-state>
                <amp-state id="EX" src="json/EX.json"></amp-state>
                <amp-state id="WL" src="json/WL.json"></amp-state>
                 
                <amp-list height="70" layout="fixed-height" src="json/inv-dropdowns.json">
                    <template type="amp-mustache">
                        <label for="type">Machine Type</label>
                        <select id="type" name="typeFilter" class="inv-dropdown" on="change:AMP.setState({inventory: {searchType: event.value,listSrc: 'json/'+event.value+'.json'},oem: dropdown.items[0].type.filter(x => x.FilterName == event.value)[0]})">
                             <option value="all">Select your machine type</option>
                            {{#type}}
                            <option value="{{FilterName}}">{{name}}</option>
                            {{/type}}
                        </select>
                    </template>
                </amp-list>
                
                <amp-list height="70" layout="fixed-height" [src]="oem" src="json/inv-dropdowns.json">
                    <template type="amp-mustache">
                        <label for="oem">Machine Make</label>
   
                        <select id="oem" name="oemFilter" [disabled]="!oem" disabled class="inv-dropdown" on="change:AMP.setState({inventory: {listSrc: {{FilterName}}.items.filter(b => event.value == 'all' ? true : b.Part_CommercialBrand == event.value)}})">
                            <option value="all">Select your machine make</option>
                            {{#oem}}
                            <option value="{{make}}">{{make}}</option>
                            {{/oem}}
                        </select>
                    </template>                
                </amp-list>
                
				<!-- Active this later when amp-bind can allow more than 50 operands, may recode to use server side filtering -->
                <!--<amp-list height="70" layout="fixed-height" [src]="model" src="json/inv-dropdowns.json">
                    <template type="amp-mustache">
                        <label for="model">Machine Make</label>
                        <select id="model" [disabled]="!model" disabled class="inv-dropdown">
                            {{^model}}
                            <option value="">Select your machine model</option>{{/model}} {{#model.0}}
                            <option value="">Select your machine model</option>{{/model.0}} {{#model}}
                            <option value="{{.}}">{{.}}</option>{{/model}}
                        </select>
                    </template>                
                </amp-list>-->
                
                <amp-state id="dropdown" src="json/inv-dropdowns.json"></amp-state>
                        
            </div>
        
            <div class="inv-wrapper">

                <div class="inv-list-row">
                
                    <div class="inv-list-header">Serial Number</div>
                    <div class="inv-list-header">BOM Number</div>
                    <div class="inv-list-header">Description</div>
                    <div class="inv-list-header">Machine Make</div>
                    <div class="inv-list-header">Machine Model/Category</div>
                    <div class="inv-list-header">Machine</div>
                
                </div>
                
                <amp-list width="auto" height="<?php echo file_get_contents('ABSOLUTE DIRECTORY PATH/count.txt')*70; ?>" [height]="inventory.listSrc.length*57" layout="fixed-height" src="json/all.json" [src]="inventory.listSrc">  
                    <template type="amp-mustache">
                        <div class="inv-list-row">
                            <div class="inv-list-item inventory">{{SerialNo_SerialNumber}}</div>
                            <div class="inv-list-item inventory">{{Part_PartNum}}</div>
                            <div class="inv-list-item inventory">{{Part_PartDescription}}</div>
                            <div class="inv-list-item inventory">{{Part_CommercialBrand}}</div>
                            <div class="inv-list-item inventory">{{Part_CommercialSubBrand}}</div>
                            <div class="inv-list-item inventory">{{Type}}</div>
                        </div> 
                    </template> 
                </amp-list>
                
            </div>