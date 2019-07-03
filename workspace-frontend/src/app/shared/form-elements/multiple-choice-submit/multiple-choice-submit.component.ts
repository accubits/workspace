import { MultipleChoicePreviewComponent } from './../multiple-choice-preview/multiple-choice-preview.component';
import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-multiple-choice-submit',
  templateUrl: './multiple-choice-submit.component.html',
  styleUrls: ['./multiple-choice-submit.component.scss']
})
export class MultipleChoiceSubmitComponent implements OnInit {

  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
  showChoice=false;


  /* Data model for multiple choice element */
  multipleChoiceElement = {
    componentId: null,
    action: 'create',
    type: 'multipleChoice',
    multipleChoice: {
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

  /*  Submit model for multiple choice element */
  multipleChoiceSubmit :any = {
    componentId: 7,
    type: 'multipleChoice',
    multipleChoice: {
      chosen: []
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.multipleChoiceElement = this.data;
      this.multipleChoiceSubmit.componentId = this.multipleChoiceElement.componentId;
      if(!this.multipleChoiceElement.hasOwnProperty('elementToSubmit')){
       let selChoice = this.multipleChoiceElement.multipleChoice.choices.filter(
          choice => choice.selected === true);
    
         
        if(selChoice){
          for(let i=0;i<selChoice.length;i++){
            this.multipleChoiceSubmit.multipleChoice.chosen.push(selChoice[i].optId);
          }
        }
        this.multipleChoiceElement.elementToSubmit = this.multipleChoiceSubmit;
     }else{
      this.multipleChoiceSubmit = this.multipleChoiceElement.elementToSubmit;
     }

    

      this.validateElement();

    }, 100)
  }

   /* Entering the selected choices for submit */
   manageChoice(index, optID): void {
     this.multipleChoiceElement.multipleChoice.choices[index].selected = !this.multipleChoiceElement.multipleChoice.choices[index]['selected']
     this.multipleChoiceElement.multipleChoice.choices[index].selected ?
      this.multipleChoiceSubmit.multipleChoice.chosen.push(optID) :
      this.multipleChoiceSubmit.multipleChoice.chosen.splice(this.multipleChoiceSubmit.multipleChoice.chosen.indexOf(optID), 1);
      this.showChoice = !this.showChoice;
      this.validateElement();

    }

  /* Validating Element[Start] */
  validateElement():void{
    this.multipleChoiceElement.multipleChoice.isRequired && this.multipleChoiceSubmit.multipleChoice.chosen.length === 0?
    this.multipleChoiceElement.isValidated =  false:this.multipleChoiceElement.isValidated =  true;
    this.multipleChoiceElement.isValidated? this.multipleChoiceElement.isValidFormat = true:this.multipleChoiceElement.isValidFormat = false;

  }
   /* Validating Element[End] */

}
