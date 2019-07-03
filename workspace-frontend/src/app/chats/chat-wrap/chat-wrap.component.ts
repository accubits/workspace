import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ChatDataService } from '../../shared/services/chat-data.service';

@Component({
  selector: 'app-chat-wrap',
  templateUrl: './chat-wrap.component.html',
  styleUrls: ['./chat-wrap.component.scss']
})
export class ChatWrapComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
