import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AwaitingCurrentDetailComponent } from './awaiting-current-detail.component';

describe('AwaitingCurrentDetailComponent', () => {
  let component: AwaitingCurrentDetailComponent;
  let fixture: ComponentFixture<AwaitingCurrentDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AwaitingCurrentDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AwaitingCurrentDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
