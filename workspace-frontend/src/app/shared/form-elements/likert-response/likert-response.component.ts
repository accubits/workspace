import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-likert-response',
  templateUrl: './likert-response.component.html',
  styleUrls: ['./likert-response.component.scss']
})
export class LikertResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for likert*/
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
          "column": ""
        },
       
      ],
      "answer": [
        
      ]
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.likertElement = this.data;
   }, 100)
  }

  /* checking the selected statements */
  checkItem(statementId,columnId):any{
   // setTimeout(() => {
    let checked = this.likertElement.likert.answer.filter(
      answer => answer.stmtId === statementId && answer.colId === columnId )[0];

      if(checked){
        return true;
      }else{
        return false
      }
 //   }, 500)

  }
}


