import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AwaitingDetailComponent } from './awaiting-detail.component';

describe('AwaitingDetailComponent', () => {
  let component: AwaitingDetailComponent;
  let fixture: ComponentFixture<AwaitingDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AwaitingDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AwaitingDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
