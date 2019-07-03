import { Component, OnInit } from '@angular/core';
//import { Configs } from '../../config';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-publish-pop',
  templateUrl: './publish-pop.component.html',
  styleUrls: ['./publish-pop.component.scss']
})
export class PublishPopComponent implements OnInit {

  constructor(
    //public assetUrl = Configs.assetBaseUrl,
    public superAdminDataService : SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  closeDetails(){
    this.superAdminDataService.publishPop.show = false;
  }
}
