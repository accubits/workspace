import { Component, OnInit,EventEmitter, Output} from '@angular/core';
import { AuthorizedSandbox } from '../../authorized.sandbox';
import { ClockDataService } from '../../../shared/services/clock.data.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../../shared/services/toast.service';

@Component({
  selector: 'app-work-hours-resume',
  templateUrl: './work-hours-resume.component.html',
  styleUrls: ['./work-hours-resume.component.scss']
})
export class WorkHoursResumeComponent implements OnInit {
  @Output() clockResume = new EventEmitter();
  currentDate  = new Date();
  constructor(
    public authorizedSandbox : AuthorizedSandbox,
    public clockDataService : ClockDataService,
    private spinner: Ng4LoadingSpinnerService,
    private toastService: ToastService,
  ) { }

  ngOnInit() {
  }

   /* Function stop or pause clock[Start] */
   doClockOutorContinue(status):void{
    this.spinner.show();
    this.clockDataService.clockInput.status = status;
    this.authorizedSandbox.getClockStatus().subscribe((result: any) => {
 
     if (result.status === 'OK') {
         this.clockDataService.clockStatus =  result.data;
         this.clockResume.emit(status);
     }
     this.spinner.hide();
   },
     err => {
      this.toastService.Error('', err.msg)
       this.spinner.hide(); 
    })
 }
 /* Function stop or pause clock[End] */

  /* Edit work hours[Start] */
  editWorkHours():void{
    this.clockDataService.clockManagement.editWork =  true;
  }
  /* Edit work hours[End] */

  showReport():void{
    this.clockDataService.clockManagement.showWorkReport =  true;
 }


}
