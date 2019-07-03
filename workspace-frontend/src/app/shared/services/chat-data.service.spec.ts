import { TestBed, inject } from '@angular/core/testing';

import { ChatDataService } from './chat-data.service';

describe('ChatDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [ChatDataService]
    });
  });

  it('should be created', inject([ChatDataService], (service: ChatDataService) => {
    expect(service).toBeTruthy();
  }));
});
