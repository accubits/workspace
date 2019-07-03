import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OngoingTrainingDetailsComponent } from './ongoing-training-details.component';

describe('OngoingTrainingDetailsComponent', () => {
  let component: OngoingTrainingDetailsComponent;
  let fixture: ComponentFixture<OngoingTrainingDetailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OngoingTrainingDetailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OngoingTrainingDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
