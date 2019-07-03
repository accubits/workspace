import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AwaitingActivationComponent } from './awaiting-activation.component';

describe('AwaitingActivationComponent', () => {
  let component: AwaitingActivationComponent;
  let fixture: ComponentFixture<AwaitingActivationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AwaitingActivationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AwaitingActivationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
