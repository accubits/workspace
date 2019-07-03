import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-form-share',
  templateUrl: './form-share.component.html',
  styleUrls: ['./form-share.component.scss']
})
export class FormShareComponent implements OnInit {
  activeRpTab = 'people';
  addpple : string = 'all';
  showMore = false;
  showActions : boolean = false;
  searchText = '';

  constructor(
    public superAdminDataService: SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  closeShareOption(){
    this.superAdminDataService.sharePop.show = false;
  }

  showActionPop(){
    this.showActions =! this.showActions;
  }
}
