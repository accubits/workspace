import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { AuthorizedSandbox } from '../../authorized.sandbox';
import { ClockDataService } from '../../../shared/services/clock.data.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../../shared/services/toast.service';
import { UtilityService } from '../../../shared/services/utility.service';


@Component({
  selector: 'app-work-hours-continue',
  templateUrl: './work-hours-continue.component.html',
  styleUrls: ['./work-hours-continue.component.scss']
})
export class WorkHoursContinueComponent implements OnInit {
  @Output() clockContinue = new EventEmitter();
  currentDate = new Date();


  constructor(
    public authorizedSandbox: AuthorizedSandbox,
    public clockDataService: ClockDataService,
    private spinner: Ng4LoadingSpinnerService,
    private toastService: ToastService,
    private utilityService: UtilityService
  ) { }

  ngOnInit() {
    //this.doEarlyClockOut();
  }


  /* Edit work hours[Start] */
  editWorkHours(): void {
    this.clockDataService.clockManagement.editWork = true;
  }
  /* Edit work hours[End] */

  /* Continue working after early clockout[Start] */
  continueWork() {
    //alert("dfsd")
    this.spinner.show();
    this.clockDataService.clockInput.status = 'clockContinue';
    this.authorizedSandbox.getClockStatus().subscribe((result: any) => {

      if (result.status === 'OK') {
        this.clockDataService.clockManagement.showTimer = false;
        this.clockDataService.clockStatus = result.data;
        console.log(this.clockDataService.clockManagement.stratTime);
        this.clockDataService.clockManagement.stratTime = result.data.lastRecordedTime;
        this.clockContinue.emit();
      }
      this.spinner.hide();
      this.clockDataService.resetClockInput();
    },
      err => {
        this.toastService.Error('', err.message)
        this.spinner.hide();
      })
  }
  /* Continue working after early clockout[End] */

  doEarlyClockOut() {
    this.spinner.show();
    this.clockDataService.clockInput.status = 'earlyClockout';
    this.authorizedSandbox.getClockStatus().subscribe((result: any) => {

      if (result.status === 'OK') {
        this.clockDataService.clockManagement.showTimer = false;
        this.clockDataService.clockStatus = result.data;
        this.clockDataService.clockManagement.stratTime = this.clockDataService.clockStatus.lastRecordedTime;
       // this.clockContinue.emit();
      }
      this.spinner.hide();
      //this.clockDataService.resetClockInput();
    },
      err => {
        this.toastService.Error('', err.message)
        this.spinner.hide();
      })
  }
  /* Continue working after early clockout[End] */

  showReport(): void {
    this.clockDataService.clockManagement.showWorkReport = true;
  }


}
