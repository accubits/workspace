import { Component, OnInit,EventEmitter, Output} from '@angular/core';
import { AuthorizedSandbox } from '../../authorized.sandbox';
import { ClockDataService } from '../../../shared/services/clock.data.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../../shared/services/toast.service';
import { UtilityService } from '../../../shared/services/utility.service';


@Component({
  selector: 'app-edit-work',
  templateUrl: './edit-work.component.html',
  styleUrls: ['./edit-work.component.scss']
})
export class EditWorkComponent implements OnInit {
   timeDiff:string

  constructor(
    public authorizedSandbox : AuthorizedSandbox,
    public clockDataService : ClockDataService,
    private spinner: Ng4LoadingSpinnerService,
    private toastService: ToastService,
    private utilityService: UtilityService
  ) { }

  ngOnInit() {
  }
  closeEdit(): void{
    this.clockDataService.clockManagement.editWork =  false;
  }


  /* Fetch workday[Start] */
  fetchWorkDay():void{
     this.clockDataService.fetchWorkDayInput.selectDate = this.utilityService.convertToUnix(this.clockDataService.editWorkTime.workDate);
     this.authorizedSandbox.fetchWorkDay();
  }
  /* Fetch workday[End] */

  /* calculate time diffrence between start and end time */
  claculateTimeDiff(){
    let startTime = this.utilityService.convertToUnix(this.clockDataService.editWorkTime.startTime);
    let endTime = this.utilityService.convertToUnix(this.clockDataService.editWorkTime.endTime);
    if(startTime && endTime){
      if(startTime > endTime){
        this.toastService.Error('','Start time should not be a time after end time')
        return;
      }
      this.timeDiff =  this.utilityService.calculateDuaration(startTime * 1000,endTime * 1000 )
    }
  }

  /* Update Work Hours[Start] */
  validateWorkHours():any{
    if(!this.clockDataService.editWorkTime.workDate){
      this.toastService.Error('','Select a Date');
      return false
    }

    if(!this.clockDataService.editWorkTime.startTime && !this.clockDataService.editWorkTime.endTime){
      this.toastService.Error('','Enter both start and time');
      return false
    }

    if(!this.clockDataService.editWorkTime.note){
      this.toastService.Error('','Enter a note');
      return false
    }

    return true;
  }

  updateWorkHours():void{
    //alert("dsdfsdf");
    if(!this.validateWorkHours())return;
    // Setting Date in selected times

    this.clockDataService.editWorkTime.workDate = this.clockDataService.editWorkTime.workDate.toDate();
    this.clockDataService.editWorkTime.startTime = this.clockDataService.editWorkTime.startTime.toDate();
    this.clockDataService.editWorkTime.endTime = this.clockDataService.editWorkTime.endTime.toDate();
    this.clockDataService.editWorkTime.startTime.setDate( this.clockDataService.editWorkTime.workDate.getDate());
    this.clockDataService.editWorkTime.startTime.setMonth( this.clockDataService.editWorkTime.workDate.getMonth());
    this.clockDataService.editWorkTime.startTime.setFullYear(this.clockDataService.editWorkTime.workDate.getFullYear());

    this.clockDataService.editWorkTime.endTime.setDate( this.clockDataService.editWorkTime.workDate.getDate());
    this.clockDataService.editWorkTime.endTime.setMonth( this.clockDataService.editWorkTime.workDate.getMonth());
    this.clockDataService.editWorkTime.endTime.setFullYear(this.clockDataService.editWorkTime.workDate.getFullYear());


    this.clockDataService.editWorkTime.workDate = this.utilityService.convertToUnix(this.clockDataService.editWorkTime.workDate);
    this.clockDataService.editWorkTime.startTime = this.utilityService.convertToUnix(this.clockDataService.editWorkTime.startTime);
    this.clockDataService.editWorkTime.endTime = this.utilityService.convertToUnix(this.clockDataService.editWorkTime.endTime);

    this.authorizedSandbox.updateWorkHours();

  }
  /* Update Work Hours[End] */

}
