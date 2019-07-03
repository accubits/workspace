import { HrmDataService } from './../../shared/services/hrm-data.service';
import {HrmSandboxService} from './../hrm.sandbox'
import { Component, OnInit } from '@angular/core';
import {ToastService} from '../../shared/services/toast.service'

@Component({
  selector: 'app-new-module',
  templateUrl: './new-module.component.html',
  styleUrls: ['./new-module.component.scss']
})
export class NewModuleComponent implements OnInit {
  selectType: boolean = false;
  selectListQstn: boolean = false;
  typeOfAnswer:string;

  isValidate = true;
  typeStatus:string = 'answerText';
  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService:HrmSandboxService,
    private toastService: ToastService,

  ) { }

  ngOnInit() {
  }

  hideNewModule() {
    this.hrmDataService.newModule.show = false;
    this.hrmDataService.showEditPopup.show = false;
    
   // this.hrmDataService.createPerformance.questions = [];
    this.hrmDataService.resetPerformance();
    for(let i=0;i<this.hrmDataService.createPerformance.questions.length;i++){
      this.hrmDataService.createPerformance.questions[i].question = '';
    }
  }
  showSelectType() {
    this.selectType = true;
  }
  hideSelectType() {
    this.selectType = false;
    
  }
  showSelectQstn() {
    this.selectListQstn = true;
  }
  hideSelectQstn() {
    this.selectListQstn = false;
  }

  /*Create Performance[Start]*/
  createPerformance():void{
    if(!this.validateElements()) return;
    this.hrmSandboxService.addPerformance();
  }
    /*Create Performance[End]*/

    /*Validate Fields[Start]*/

    validateElements(){
      this.isValidate = true;
      if(this.hrmDataService.createPerformance.questions.length === 0){
        this.toastService.Error('Please add a question');
       }

       (!this.hrmDataService.createPerformance.title ||!this.hrmDataService.createPerformance.description)?
       this.isValidate = false : this.isValidate = true

       for(let i=0;i<this.hrmDataService.createPerformance.questions.length;i++){
         if(!this.hrmDataService.createPerformance.questions[i].question) this.isValidate=false;
       }

      return this.isValidate;
    }
   /*Validate Fields[End]*/


    /*Selected answer type[Start]*/

    questionType(typeofquestion,i):void{
      this.hrmDataService.createPerformance.questions[i].type = typeofquestion;
      this.typeStatus = typeofquestion;
      
     // this.selectType = false;

    }
        /*Selected answer type[End]*/


        /*Comment Type [Start]*/
        comment(comment,i):void{
          if(comment){
            this.hrmDataService.createPerformance.questions[i].enableComment = true;
          }
          else{
            this.hrmDataService.createPerformance.questions[i].enableComment = false;

          }

        }
       /*Comment Type [End]*/


        /*Add Question[Start]*/
       
        addQuestion():void{
        this.hrmDataService.createPerformance.questions.push({
        type: 'answerText',
        action: 'create',
        questionSlug: null,
        enableComment: false,
        question: ''
          })
        }
       /*Add Question[End]*/

       /*Remove Question[Start]*/

      //  removeQuestions(i):void{
      //    this.hrmDataService.createPerformance.questions.splice(i);

      //  }

      removeQuestions(qst,i):void{
        let qustn = this.hrmDataService.createPerformance.questions.filter(
          setQuestion => setQuestion.question === qst.question
        ) [0]

        if(qustn) {
          let idx = this.hrmDataService.createPerformance.questions.indexOf(qustn);
          if(idx != -1)
            this.hrmDataService.createPerformance.questions.splice(idx,1)
        }
        // this.hrmDataService.createPerformance.questions.splice(i);

      }
      /*Remove Question[End]*/


      /*Edit Performance[Start]*/
      editPerformance():void{

        if(!this.validateElements()) return ;
        this.hrmDataService.createPerformance.action = 'update'
        //this.hrmDataService.selectedPerformance.questions.action = 'update';

        this.hrmSandboxService.addPerformance();
      }
     /*Edit Performance[End]*/


}
