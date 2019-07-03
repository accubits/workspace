import { Component, OnInit,EventEmitter, Output} from '@angular/core';
import { AuthorizedSandbox } from '../../authorized.sandbox';
import { ClockDataService } from '../../../shared/services/clock.data.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../../shared/services/toast.service';
import { UtilityService } from '../../../shared/services/utility.service';

@Component({
  selector: 'app-previous-day',
  templateUrl: './previous-day.component.html',
  styleUrls: ['./previous-day.component.scss']
})
export class PreviousDayComponent implements OnInit {
  @Output() clockOutPrevios = new EventEmitter();
  lastDate =  null;
  previousStarttime = null;
  previosClockOutTime  = null;
  timeDiff:string;
  lastClockinTimeStamp = null

  constructor(
    public authorizedSandbox : AuthorizedSandbox,
    public clockDataService : ClockDataService,
    private spinner: Ng4LoadingSpinnerService,
    private toastService: ToastService,
    private utilityService: UtilityService
  ) { }

  ngOnInit() {
    this.lastDate =  new Date(this.clockDataService.clockStatus.startTime *  1000)
  }
  closeClockOut(): void{
    this.clockDataService.clockManagement.workReport = false;
  }

  setPreviousClock():void{
    console.log(this.previosClockOutTime)
  
    // this.lastDate.setHours(this.previosClockOutTime.getHours());
    // this.lastDate.setMinutes(this.previosClockOutTime.getMinutes());
    // this.lastDate.setSeconds(this.previosClockOutTime.getSeconds());
    
    // using moment date
    this.lastDate.setHours(this.previosClockOutTime.hours());
    this.lastDate.setMinutes(this.previosClockOutTime.minutes());
    this.lastDate.setSeconds(this.previosClockOutTime.seconds());

    this.lastClockinTimeStamp = this.utilityService.convertToUnix(this.lastDate);
    if(this.clockDataService.clockStatus.startTime > this.lastClockinTimeStamp ){
      this.toastService.Error('', 'Your previous clockout time is before previous clockin time')
      return;
    }
    this.timeDiff =  this.utilityService.calculateDuaration(this.clockDataService.clockStatus.startTime * 1000,this.lastClockinTimeStamp * 1000 )

  }

  doClockOut():void{
      if(!this.lastClockinTimeStamp){
        this.toastService.Error('', 'Select previous day clockout time')
        return;
      }

      if(!this.clockDataService.previosDayInput.note){
        this.toastService.Error('', 'Enter resaon')
        return;
      }
      this.clockDataService.previosDayInput.currentTime = this.utilityService.convertToUnix(new Date());
      this.clockDataService.previosDayInput.clockOutTime =  this.utilityService.convertToUnix(this.lastDate);

      this.authorizedSandbox.clockOutPreviosDay().subscribe((result: any) => {
  
        if (result.status === 'OK') {
            this.clockDataService.clockStatus =  result.data;
            this.clockDataService.resetPreviousDay();
            this.clockOutPrevios.emit();
        }
        this.spinner.hide();
      },
        err => {
         this.toastService.Error('', err.msg)
          this.spinner.hide(); 
       })

  }
}
