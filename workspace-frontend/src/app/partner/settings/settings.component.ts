import { Component, OnInit } from '@angular/core';
import {ToastService} from '../../shared/services/toast.service'
import {PartnerDataService} from '../../shared/services/partner-data.service';
import {PartnerSandbox} from './../partner.sandbox'

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {
  image: string;

  constructor(
    public toastService:ToastService,
    public partnerDataService:PartnerDataService,
    public partnerSandbox:PartnerSandbox


  ) { }

  ngOnInit() {
  }

   /* File upload[Start] */

   uploadFile(files) {

    if ((files[0].size / (1024 * 1024)) > 5) {
      this.toastService.Error("File size can't be grater than 5 MB");
    }
    else{
      if (files && files[0]) {
        this.partnerDataService.changeImage.file[0] = files[0] 
       var reader = new FileReader();
       reader.onload = (event: ProgressEvent) => {
         this.image = (<FileReader>event.target).result;
       }
       reader.readAsDataURL(files[0]);
     }
    }
   
    // this.hideRemove = false;

  }

  /* File upload[End] */

  changePicture(){
    this.partnerSandbox.editSettingsImage();
  }

}
