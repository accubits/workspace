import { Component, OnInit,Output,EventEmitter } from '@angular/core';
import { AuthorizedSandbox } from '../../authorized.sandbox';
import { ClockDataService } from '../../../shared/services/clock.data.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../../shared/services/toast.service';

@Component({
  selector: 'app-work-hours-start',
  templateUrl: './work-hours-start.component.html',
  styleUrls: ['./work-hours-start.component.scss']
})
export class WorkHoursStartComponent implements OnInit {
  @Output() clockIn = new EventEmitter();
  currentDate  = new Date();

  constructor(
    public authorizedSandbox : AuthorizedSandbox,
    public clockDataService : ClockDataService,
    private spinner: Ng4LoadingSpinnerService,
    private toastService: ToastService,
  ) { }

  ngOnInit() {
  }

  /* Function start clock[Start] */
  doClockin():void{
     this.spinner.show();
     this.clockDataService.clockInput.status = 'clockIn';
     
     this.authorizedSandbox.getClockStatus().subscribe((result: any) => {
  
      if (result.status === 'OK') {
          this.clockDataService.clockStatus =  result.data;
          this.clockIn.emit();
      }
      this.spinner.hide();
      this.clockDataService.resetClockInput();
    },
      err => {
       this.toastService.Error('', err.msg)
        this.spinner.hide(); 
     })
  }
  /* Function start clock[End] */

  /* Edit work hours[Start] */
  editWorkHours():void{
    this.clockDataService.clockManagement.editWork =  true;
  }
  /* Edit work hours[End] */

  showReport():void{
    this.clockDataService.clockManagement.showWorkReport =  true;
 }

}
