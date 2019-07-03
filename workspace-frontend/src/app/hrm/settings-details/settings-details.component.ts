import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-settings-details',
  templateUrl: './settings-details.component.html',
  styleUrls: ['./settings-details.component.scss']
})
export class SettingsDetailsComponent implements OnInit {
  budgetValidated = true;
  daysValidated = true;
  checkRole: string= '';
  
  constructor(public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    public cookieService: CookieService) { }

  ngOnInit() {
    this.checkRole  = this.cookieService.get('role');
  }

   /* Validating creating new message[Start] */
   validateBalenceAmmount(): boolean {
    this.budgetValidated = true;
    if (this.hrmDataService.trainingStatus.trainingBudget.totalBalance === '') this.budgetValidated = false;
    if (this.hrmDataService.trainingStatus.trainingBudget.totalBalance === '0') this.budgetValidated = false;
    return this.budgetValidated;
  }
  /* Validating creating new message[end] */

  updateBalance(){
    if (!this.validateBalenceAmmount()) return;
    this.hrmSandboxService.setTrainingBudget();
  }

 /* Validating creating new message[Start] */
 validateFeedbackDays(): boolean {
  this.daysValidated = true;
  if (this.hrmDataService.trainingStatus.trainingFeedback.days === '') this.daysValidated = false;
  if (this.hrmDataService.trainingStatus.trainingFeedback.days === '0') this.daysValidated = false;
  return this.daysValidated;
}
/* Validating creating new message[end] */

  updateFeedback(){
    if (!this.validateFeedbackDays()) return;
    this.hrmSandboxService.setTrainingFeedback();
  }

}
