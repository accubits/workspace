import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-create-form',
  templateUrl: './create-form.component.html',
  styleUrls: ['./create-form.component.scss']
})
export class CreateFormComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  closePop(){
    this.superAdminDataService.createForm.show = false;
  }
}
