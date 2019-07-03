import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActiveTrainingDetailComponent } from './active-training-detail.component';

describe('ActiveTrainingDetailComponent', () => {
  let component: ActiveTrainingDetailComponent;
  let fixture: ComponentFixture<ActiveTrainingDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActiveTrainingDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActiveTrainingDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
