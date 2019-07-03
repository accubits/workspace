import { SettingsDataService } from './../../shared/services/settings-data.service';
import { Component, OnInit } from '@angular/core';
import {ToastService} from '../../shared/services/toast.service';
import { SettingsSandbox } from './../settings.sandbox'


@Component({
  selector: 'app-organization-setting',
  templateUrl: './organization-setting.component.html',
  styleUrls: ['./organization-setting.component.scss']
})
export class OrganizationSettingComponent implements OnInit {
image:string;
  constructor(
    public settingsDataService: SettingsDataService,
    public toastService:ToastService,
    public settingsSandbox:SettingsSandbox
  ) { }
  periodSelect: boolean = false;
  timeList: boolean = false;

  ngOnInit() {
    this.settingsSandbox.getSettings();
    this.settingsDataService.changeBackgroundSettings.dashboardMsg = this.settingsDataService.getBackgroundSettings.dashboardSettings.dashboardMsg;
    this.image = this.settingsDataService.getBackgroundSettings.dashboardSettings.imageUrl; 
  }
  showPeriodListing() {
    this.periodSelect = true;
  }

  closeBox(){
    this.timeList = false;
  }
  showBox(){
    this.timeList =! this.timeList;
  }
  /* File upload[Start] */

  uploadFile(files) {

    if ((files[0].size / (1024 * 1024)) > 5) {
      this.toastService.Error("File size can't be grater than 5 MB");
    }
    else{
      if (files && files[0]) {
        this.settingsDataService.changeBackgroundSettings.file[0] = files[0] 
       var reader = new FileReader();
       reader.onload = (event: ProgressEvent) => {
         this.image = (<FileReader>event.target).result;
       }
       reader.readAsDataURL(files[0]);
     }
    }

  }

  /* File upload[End] */

  SaveSettings(){
    this.settingsSandbox.editBackgroundSettingsImage();
  }


  timeZoneType(type){
    this.settingsDataService.changeBackgroundSettings.timezone = type;
  }
}
