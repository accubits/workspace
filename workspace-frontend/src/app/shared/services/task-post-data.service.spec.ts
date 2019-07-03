import { TestBed, inject } from '@angular/core/testing';

import { TaskPostDataService } from './task-post-data.service';

describe('TaskPostDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TaskPostDataService]
    });
  });

  it('should be created', inject([TaskPostDataService], (service: TaskPostDataService) => {
    expect(service).toBeTruthy();
  }));
});
