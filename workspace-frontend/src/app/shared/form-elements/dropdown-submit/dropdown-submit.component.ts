import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-dropdown-submit',
  templateUrl: './dropdown-submit.component.html',
  styleUrls: ['./dropdown-submit.component.scss']
})
export class DropdownSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
  showChoice=false;
  selectedOption = '';


  /* Data Model for dropdown element */
  dropdownElement = {
    componentId: null,
    action: 'create',
    type: 'dropdown',
    dropdown: {
      label: '',
      choices: [
        {
          optId: null,
          fqoSortNo: null,
          label: '',
          maxQuantity: null,
          alreadySelectedCount: null,
          selected: false
        },
      ],
      isRequired: false,
      randomize: false,
      allowOther: false,
      otherLabel: ''
    },
    elementToSubmit: {},
    isValidated:true,
    isValidFormat:true

  }

  /* Data model for dropdown submit */
  dropownsubmit : any = {
    componentId: null,
    type: 'dropdown',
    dropdown: {
      chosen: [],
      itemName:'',

    }
  }


  ngOnInit() {
    setTimeout(() => {
      this.dropdownElement = this.data;
      this.dropownsubmit.componentId = this.dropdownElement.componentId;
      if(!this.dropdownElement.hasOwnProperty('elementToSubmit')){
        this.dropdownElement.elementToSubmit = this.dropownsubmit;
     }else{
      this.dropownsubmit = this.dropdownElement.elementToSubmit;
      this.selectedOption = this.dropownsubmit.dropdown.itemName; 

      
     }

     let selChoice = this.dropdownElement.dropdown.choices.filter(
      choice => choice.selected === true)[0];

      if(selChoice){
        this.dropownsubmit.dropdown.chosen = [];
        this.selectedOption = selChoice.label;
        this.dropownsubmit.dropdown.chosen.push(selChoice.optId);
      }
      
      this.validateElement();
    }, 100)
  }

  /* Entering the selected choices for submit */
  manageChoice(optID,item): void {
   this.selectedOption = item
   this.dropownsubmit.dropdown.itemName = item
   this.dropownsubmit.dropdown.chosen = [];
   this.dropownsubmit.dropdown.chosen.push(optID);
   this.showChoice= false;
   this.validateElement(); 

 }
  /* Validating Element[Start] */
  validateElement():void{
    this.dropdownElement.dropdown.isRequired && this.dropownsubmit.dropdown.chosen.length === 0?
    this.dropdownElement.isValidated =  false:this.dropdownElement.isValidated =  true;
    this.dropdownElement.isValidated? this.dropdownElement.isValidFormat = true:this.dropdownElement.isValidFormat = false;

   }
   /* Validating Element[End] */

}
