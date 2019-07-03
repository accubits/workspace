import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OngoingTrainRequestDetailComponent } from './ongoing-train-request-detail.component';

describe('OngoingTrainRequestDetailComponent', () => {
  let component: OngoingTrainRequestDetailComponent;
  let fixture: ComponentFixture<OngoingTrainRequestDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OngoingTrainRequestDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OngoingTrainRequestDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
