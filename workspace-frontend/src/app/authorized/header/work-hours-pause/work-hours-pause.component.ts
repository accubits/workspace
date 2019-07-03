import { Component, OnInit,EventEmitter, Output} from '@angular/core';
import { AuthorizedSandbox } from '../../authorized.sandbox';
import { ClockDataService } from '../../../shared/services/clock.data.service';
import { ClockApiService } from '../../../shared/services/clock-api.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../../shared/services/toast.service';


@Component({
  selector: 'app-work-hours-pause',
  templateUrl: './work-hours-pause.component.html',
  styleUrls: ['./work-hours-pause.component.scss']
})
export class WorkHoursPauseComponent implements OnInit {
  @Output() clockIn = new EventEmitter();
  @Output() clockOut = new EventEmitter();
  currentDate  = new Date();

  constructor(
    public authorizedSandbox : AuthorizedSandbox,
    public clockDataService : ClockDataService,
    public clockApiService :ClockApiService,
    private spinner: Ng4LoadingSpinnerService,
    private toastService: ToastService,
  ) { }

  ngOnInit() {
    
  }

    /* Function stop or pause clock[Start] */
    doClockOutorPause(status):void{
      //alert("fdfg");
      this.spinner.show();
      this.clockDataService.clockInput.status = status;
      this.authorizedSandbox.getClockStatus().subscribe((result: any) => {
   
       if (result.status === 'OK') {
           this.clockDataService.clockStatus =  result.data;
           this.clockOut.emit(status);
          //  this.clockDataService.resetClockInput();
           //this.clockDataService.clockManagement.stratTime = result.data.lastRecordedTime;
           console.log(result.data);
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
