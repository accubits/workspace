import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-appraisal-form',
  templateUrl: './appraisal-form.component.html',
  styleUrls: ['./appraisal-form.component.scss']
})
export class AppraisalFormComponent implements OnInit {
  selectNum : boolean = false;
  marketingPop : boolean = false;
  communicatePop : boolean = false;
  clickBox : boolean = true;
  currentBox : boolean = false;
  // optionList : boolean = false;

  constructor() { }

  ngOnInit() {
  }

  option(){
    this.selectNum =! this.selectNum;
    this.clickBox = ! this.clickBox;
    this.currentBox =! this.currentBox;
    // this.optionList = true;
  }

  // hideOption(){
  //   this.optionList = false;
  // }

  showMarketing(){
    this.marketingPop =! this.marketingPop;
  }

  closeOption(){
    this.selectNum = false;
  }

  showCommmunication(){
    this.communicatePop =! this.communicatePop;
  }
}
