import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-checkboxes-submit',
  templateUrl: './checkboxes-submit.component.html',
  styleUrls: ['./checkboxes-submit.component.scss']
})
export class CheckboxesSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* Data model for checkbox element */
  checkboxElement = {
    componentId: null,
    action: 'create',
    type: 'checkboxes',
    checkboxes: {
      label: '',
      choices: [
        {
          optId: null,
          action: 'create',
          fqoSortNo: null,
          label: '',
          maxQuantity: null,
          alreadySelectedCount: null,
           selected: false
        },
      ],
      isRequired: false

    },
    elementToSubmit: {},
    isValidated:true,
    isValidFormat:true
    

  }

  /* Data model for checkbox submit */
  checkboxSubmit : any= {
    componentId: 6,
    type: 'checkboxes',
    checkboxes: {
      chosen: []
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.checkboxElement = this.data;
      this.checkboxSubmit.componentId = this.checkboxElement.componentId;
      if(!this.checkboxElement.hasOwnProperty('elementToSubmit')){
        let selChoice = this.checkboxElement.checkboxes.choices.filter(
          choice => choice.selected === true);
    
        if(selChoice){
          for(let i=0;i<selChoice.length;i++){
            this.checkboxSubmit.checkboxes.chosen.push(selChoice[i].optId);
          }
        }
        this.checkboxElement.elementToSubmit = this.checkboxSubmit;
     }else{
      this.checkboxSubmit = this.checkboxElement.elementToSubmit;
     }
      this.validateElement(); 

    }, 100)
  }

  /* Entering the selected choices for submit */
  manageChoice(isSelected, optID): void {
    isSelected ?
      this.checkboxSubmit.checkboxes.chosen.push(optID) :
      this.checkboxSubmit.checkboxes.chosen.splice(this.checkboxSubmit.checkboxes.chosen.indexOf(optID), 1);
      this.validateElement(); 
  }

   /* Validating Element[Start] */
   validateElement():void{
    this.checkboxElement.checkboxes.isRequired && this.checkboxSubmit.checkboxes.chosen.length === 0?
       this.checkboxElement.isValidated =  false:this.checkboxElement.isValidated =  true;
       this.checkboxElement.isValidated? this.checkboxElement.isValidFormat = true:this.checkboxElement.isValidFormat = false;

   }
   /* Validating Element[End] */
}
