import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ChatDataService } from '../../shared/services/chat-data.service';


@Component({
  selector: 'app-chat-new-pop',
  templateUrl: './chat-new-pop.component.html',
  styleUrls: ['./chat-new-pop.component.scss']
})
export class ChatNewPopComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
