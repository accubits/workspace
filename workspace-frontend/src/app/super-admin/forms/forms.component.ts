import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-forms',
  templateUrl: './forms.component.html',
  styleUrls: ['./forms.component.scss']
})
export class FormsComponent implements OnInit {

  constructor(
    public superAdminDataService : SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  openForm(){
    this.superAdminDataService.createForm.show = true;
  }
}
