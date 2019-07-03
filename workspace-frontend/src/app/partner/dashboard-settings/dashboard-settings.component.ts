import { Component, OnInit } from '@angular/core';
import {ToastService} from '../../shared/services/toast.service'
import { PartnerDataService } from '../../shared/services/partner-data.service';
import {PartnerSandbox} from './../partner.sandbox'


@Component({
  selector: 'app-dashboard-settings',
  templateUrl: './dashboard-settings.component.html',
  styleUrls: ['./dashboard-settings.component.scss']
})
export class DashboardSettingsComponent implements OnInit {
  image: string;
  error: string;


  constructor(
    public toastService:ToastService,
    public partnerDataService:PartnerDataService,
    public partnerSandbox:PartnerSandbox
  ) { }
  timeZone: boolean = false;

  ngOnInit() {

    this.partnerDataService.changeBackgroundSettings.dashboardMsg = this.partnerDataService.getBackgroundSettings.dashboardSettings.dashboardMsg;
    this.image = this.partnerDataService.getBackgroundSettings.dashboardSettings.imageUrl; 
    this.partnerDataService.changeBackgroundSettings.timeZone = this.partnerDataService.getBackgroundSettings.dashboardSettings.timezone
  }
  showTimeZone() {
    this.timeZone = !this.timeZone;
  }

   /* File upload[Start] */

   uploadFile(files) {

    if ((files[0].size / (1024 * 1024)) > 5) {
      this.toastService.Error("File size can't be grater than 5 MB");
    }
    else{
      if (files && files[0]) {
        this.partnerDataService.changeBackgroundSettings.file[0] = files[0] 
       var reader = new FileReader();
       reader.onload = (event: ProgressEvent) => {
         this.image = (<FileReader>event.target).result;
       }
       reader.readAsDataURL(files[0]);
     }
    }

    if(files == ''){
      this.error = '  No file choosen';
    }
   
    // this.hideRemove = false;

  }

  /* File upload[End] */
  changePicture(){
    this.partnerSandbox.editBackgroundSettingsImage();
  }

  timeZoneType(type){
    this.partnerDataService.changeBackgroundSettings.timeZone = type;
  }

  discardChanges(){
    this.partnerDataService.changeBackgroundSettings.file = [];
    this.partnerDataService.resetSettings();
  }
}
