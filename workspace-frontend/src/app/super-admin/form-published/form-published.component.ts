import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-form-published',
  templateUrl: './form-published.component.html',
  styleUrls: ['./form-published.component.scss']
})
export class FormPublishedComponent implements OnInit {
  showOption : boolean = false;

  constructor(
    public superAdminDataService: SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  morepop(event){
    this.showOption =! this.showOption;
    event.stopPropagation();
  }

  check(event){
    event.stopPropagation();
  }

  openPublish(){
    this.superAdminDataService.publishPop.show = true;
  }
  closeMore(event){
    this.showOption = false;
    event.stopPropagation();
  }

  openShare(){
    this.superAdminDataService.sharePop.show = true;
  }
}

