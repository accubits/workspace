import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbLeftComponent } from './ab-left.component';

describe('AbLeftComponent', () => {
  let component: AbLeftComponent;
  let fixture: ComponentFixture<AbLeftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbLeftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbLeftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
