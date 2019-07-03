import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-likert-submit',
  templateUrl: './likert-submit.component.html',
  styleUrls: ['./likert-submit.component.scss']
})
export class LikertSubmitComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* Data model for likert element */
  likertElement = {
    "type": "likert",
    "componentId": null,
    "likert": {
      "isRequired": false,
      "label": "",
      "statements": [
        {
          "stmtId": null,
          "stmt": ""
        },
      ],
      "columns": [
        {
          "colId": null,
          "column": "",
          "selected": false
        },
        

      ],
      "answer": [
      
      ]
    },
    elementToSubmit: {},
    isValidated:true,
    isValidFormat:true
 


  }

  /*  Submit model for multiple choice element */
  likertSubmit:any = {
    "type": "likert",
    "componentId": null,
    "likert": {
      "answers": [
      
      ]
    }
  }


  ngOnInit() {
    setTimeout(() => {
      this.likertElement = this.data;
      this.likertElement.isValidated =true;
      this.likertElement.isValidFormat =true;

      this.likertSubmit.componentId = this.likertElement.componentId;
     if(!this.likertElement.hasOwnProperty('elementToSubmit')){
       if(this.likertElement.likert.answer){
        this.likertSubmit.likert.answers = this.likertElement.likert.answer;
       }
        this.likertElement.elementToSubmit = this.likertSubmit;
     }else{
      this.likertSubmit = this.likertElement.elementToSubmit;
     }
    }, 100)
  }

  /* Insert or remove in to answer */
  insertOrRemoveToAnswer(evt,statementId,columnId): void {
    let target = evt;
    if (target.checked) {
      let existingStatement = this.likertSubmit.likert.answers.filter(
        answer => answer.stmtId === statementId )[0];
      if (existingStatement) {
        let selIndex = this.likertSubmit.likert.answers.indexOf(existingStatement);
        this.likertSubmit.likert.answers.splice(selIndex,1);
      }
      this.likertSubmit.likert.answers.push({stmtId:statementId,colId:columnId})
    } else {
      let selIndex = this.likertSubmit.likert.answers.indexOf({stmtId:statementId,colId:columnId});
      this.likertSubmit.likert.answers.splice(selIndex,1);
    }
  }

  /* check Already Selected */
  checkAlreadySelected(statementId,columnId):any{
    let existingItem = this.likertSubmit.likert.answers.filter(
      answer => answer.stmtId === statementId && answer.colId === columnId )[0];
      return  existingItem? true : false;
  }

}

